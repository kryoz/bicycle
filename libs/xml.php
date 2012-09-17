<?php
class Xml extends DOMDocument{

    private
    	$_xpath = false,
    	$_xpath_xml = false;
    public
    	$formatOutput = true,
    	$preserveWhiteSpace = false;

    /**
     * создает xml документ из массива
     *
     * для передачи атрибутов используется массив с ключем "_xml_attr"
     * напр. foo => array( "_xml_attr" => array( id => 40 ) ) вернет <foo "id"="40" />
     *
     * @param mixed $xml xml объект или null
     * @param array $arr массив для преобразования
     *
     * @return object $xml
     */
    public function array2xml( $xml = null, $arr = array( ) )
    {

		if( is_null( $xml ) ){

		    $xml = new self();
		}

		if( empty( $arr ) and $arr != 0 ){

		    return $xml;
		}


		if( is_array( $arr ) ){

		    foreach( $arr as $key => $value ){


			if( ($attr = stristr( $key, '_xml_attr' ) ) !== false ){


			    foreach( $value as $k => $v ){

					$a = $this->createAttribute( $k );
					$a->appendChild( $this->createTextNode( (string) $v ) );

					$xml->appendChild( $a );
			    }

			    continue;
			}


			$n = $this->createElement( $key );


			if( is_array( $value ) ){

			    if( array_key_exists( 0, $value ) ){

				for( $i = 0, $c = sizeof( $value ); $i < $c; $i++ ){

				    $n = $this->createElement( $key );
				    $this->array2xml( $n, $value[ $i ] );

				    try{
					$xml->appendChild( $n );
				    }
				    catch ( DOMException $e){

					echo $e->getMessage();
				    }
				}
			    }
			    else{

				$this->array2xml( $n, $value );
			    }
			}
			else{


				$n->appendChild( $this->createTextNode( (string) $value ) );
			}
			    $xml->appendChild( $n );
	//		}
		    }
		}
		else{

		    $xml->appendChild( $this->createTextNode( (string) $arr ) );
		}
	return $xml;
    }



    /**
     * конвертируем xml в массив
     *
     * @todo продумать кэширование $zeroed_array!!!
     *
     * @param object $xml
     * @param string $zeroed_anyway строка с именами узлов (через пробел) - искоючений для "схлопывания"
     * @param boolean $lowercase_keys перевести ключи в нижний регистр
     * @return array
     */
    public function xml2array( $xml, $zeroed_anyway = '', $lowercase_keys = true ){

	$zeroed_anyway = ' ' . $zeroed_anyway . ' ';

	// корневой узел
	if( $xml->nodeType == XML_TEXT_NODE ){

	    return $xml->nodeValue;
	}


		if( $xml->hasAttributes() ){

		    $attributes = $xml->attributes;

		    if( !is_null( $attributes ) ){

		        try {

		                $textContent = false;
		                if( $xml->firstChild instanceof DOMText ){

		                        $textContent = $xml->textContent;
		                        $xml->removeChild( $xml->firstChild );
		                }

		                $attr_node = $xml->appendChild( new DOMElement( '_xml_attr' ) );

		                $attrs = array();
		                foreach( $attributes as $index => $attr ){

		                        $attrs[ $attr->name ] = $attr->value;
		                        $attr_node->appendChild( new DOMElement( $attr->name, $attr->value ) );
		                }

		                if( $textContent )
                                {

		                        $xml->appendChild( new DOMElement( '_xml_val', $textContent ) );
		                }

		                }
		                catch( DOMException $e ){

		                        $attrs = array();
		                        foreach( $attributes as $index => $attr ){

		                                $attrs[ $attr->name ] = $attr->value;
		                        }

		                        dump( $attrs, false, false );
		                        dump( $e->getLine(), false, false );
		                        dump( $e->getMessage(), 1, false );
		                }
		    }
		}



		$res = array();

		if( $xml->hasChildNodes() ){

	        $children = $xml->childNodes;

	        for( $i = 0, $c = $children->length; $i < $c; $i++ ){

	            $child = $children->item( $i );


	            $child_nodeName = $child->nodeName;

	            if( $lowercase_keys ){

	                $child_nodeName = mb_strtolower( $child_nodeName );
	            }


	            if( $child->nodeName == '#text' or $child->nodeName == '#cdata-section' ){

	                $res = trim( $child->nodeValue );
	                continue;
	            }

	            $xml2array = $this->xml2array( $child, $zeroed_anyway, $lowercase_keys );

	            if( mb_strpos( $zeroed_anyway, ' ' . $child_nodeName . ' ' )
	                    // или у следующего соседа такое же имя нода
	                    or ( $child->nextSibling and $child->nextSibling->nodeName == $child->nodeName ) ){

	                    // то собираем в список
	                $res[ $child_nodeName ][ ] = $xml2array;

	            }
	            else{


	                if( ( !isset( $res[ $child_nodeName ] ) or !is_array( $res[ $child_nodeName ] ) ) )
                        {

	                            if( $child_nodeName == '_xml_val' ){

	                                    $child_nodeName = 0 ;
	                            }

	                            $res[ $child_nodeName ] = $xml2array;

	                }
	                else{

	                            $res[ $child_nodeName ][ ] = $xml2array;
	                }

	            }
	        }
		}
		// нет дочерних элементов
		else{
			$res = $xml->nodeName;
		}


	return $res;
    }
}