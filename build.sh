#!/bin/bash

# TYPO3 CMS
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-master
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-6.1
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-6.0
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-4.7
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-4.6
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-4.5

# FLOW3
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-master
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-2.0
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-1.1

# FLOW3 Form
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-form-master
