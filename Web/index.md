<ul class="breadcrumbs">
	<li>
		<a href="http://typo3.org/" target="_top" title="TYPO3 - The Enterprise Open Source CMS">typo3.org</a>
	</li>
	<li>
		<a href="http://typo3.org/download/" target="_top" title="Download">Download</a>
	</li>
	<li>TYPO3 CMS Composer Repository</li>
</ul>


TYPO3 API Documentation
=======================

TYPO3 source code is well documented and allows you to understand many internals just by browsing through the various classes and methods.

<table>
    <tr>
        <td>TYPO3 CMS 7 LTS - current stable</td>
        <td><a href="http://api.typo3.org/typo3cms" target="_blank">api.typo3.org/typo3cms</a></td>
    </tr>
    <tr>
        <td style="width: 240px">TYPO3 CMS 6.2 - old stable</td>
        <td><a href="http://api.typo3.org/typo3cms/62" target="_blank">api.typo3.org/typo3cms/62</a></td>
    </tr>
    <tr>
        <td> TYPO3 CMS master</td>
        <td><a href="http://api.typo3.org/typo3cms/master" target="_blank">api.typo3.org/typo3cms/master</a></td>
    </tr>
</table>

Archives and offline reading
----------------------------

If an older version of TYPO3 is needed - or for offline consulting - [archives](http://api.typo3.org/archives/) are available for download.

How it is built?
----------------

Because we use JavaDoc style documentation, it is possible to automatically create a complete documentation of the
TYPO3 core. At the moment <a href="http://www.doxygen.org" target="_blank">Doxygen</a> is used to generate the API
of TYPO3 as it offers many neat features.

* nice layout - easy to browse
* build-in search engine
* graph of classes inheritance
* highly customizable output

Basically, a "nightly-run" script takes care of keeping the API up-to-date automatically.

How to improve?
---------------

If you have any concrete ideas / patches that would make the API better, please report onto the 
<a href="http://forge.typo3.org/projects/typo3org-api/issues" target="_blank">Bug Tracker</a>.
				
			