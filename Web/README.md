api.typo3.org
=============

This repository contains the web pages for http://api.typo3.org.

Installation
------------

To install it locally:

	# Clone the repository
	git clone git://git.typo3.org/Sites/ApiTypo3Org.git api.typo3.org

	# Download typo3.org landing page sub-module
	cd api.typo3.org
	git submodule update --init

	# Install packages dependencies
	cd api.typo3.org/Web
	composer install

Contributing
------------

To change and submit the content of this website, send patches to Gerrit:


	# Install the Gerrit Hook
	scp -p -P 29418 USERNAME@review.typo3.org:hooks/commit-msg .git/hooks/

	# After committing your changes, push to a special branch for review
	git push origin HEAD:refs/for/master

