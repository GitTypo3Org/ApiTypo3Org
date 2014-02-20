#!/bin/bash

# TYPO3 CMS
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-master
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-6.1
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-6.0
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-4.7
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-4.6
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-4.5

# EXTBASE
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-extbase-master
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-extbase-6.1
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-extbase-6.0
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-extbase-4.7
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-extbase-1.3

# FLUID
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-fluid-master
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-fluid-6.1
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-fluid-6.0
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-fluid-4.7
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-fluid-1.3

# FLOW3
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-master
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-2.0
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-1.1

# FLOW3 Form
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-flow-form-master

# INCUBATOR
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-incubator-file
