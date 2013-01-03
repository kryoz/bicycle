This is very simple MVC framework which should satisfy for purposes of:
<ul>
<li>creation of pages with SEF URL hierarhy</li>
<li>simple page structure (no dedicated entities for modules or plugins yet)</li>
<li>AJAX capability</li>
<li>various databases support via PDO</li>
<li>caching capability (file or APC)</li>
<li>Email handling</li>
<li>Basic debugging</li>
</ul>
Actually it develops because of my work where I use this framework and meet new requirements for completion of goals.<br>
Also it helps me to understand my blind spots in OOP where I'm apprentice yet/ <br><br>
If you want me help to learn best practicies in OOP you're welcome as contributor. Thank you.<br>
<br>
Framework structure:<br>
<pre>
ROOT
|
|-- components  - your application 
|-- libs        - shared libraries
|-- tmpl        - global templates for views
|
|- config.php   - configuration of framework
|- index.php    - framework entry
|- .htaccess    - Apache directives for SEF support (very simple and easily can be rewritten for nginx)
</pre>
