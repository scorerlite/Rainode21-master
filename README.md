# Rainode21

Simple and clean monitor frontend for a Rai network node based on curl and php.
It connects to a running node via RPC and displays it's status on a simple webpage:

![Rainode21img](https://github.com/stefonarch/phpNodeXRai/blob/master/preview1.png) 

Currently, the following information is displayed:
* Information text block
* Current block number
* Number of unchecked blocks
* Number of peers
* Voting Weight
* Number of delegators
* Custom info about the node and the server
* Basic Rai value information
* QR Code for Node and Donation accounts


## Installation

Instructions for setting up in a very simple way a node can be found [here](http://Rainode21.cloud/setupnode.htm)
This How-to includes a [script](https://gist.github.com/stefonarch/61d21152a0b71341e4c4a1b5a0df7795)  will configure RPC settings and install some basic comands to control the node.

You will need to install,configure and have running nginx (or apache) and phpfm to run this stuff.

*  To install in server root you need to empty it first, then change in the server root Run `git clone https://github.com/https://github.com/stefonarch/Rainode21 .`
Note the final dot!
* You can install also in <your_IP>/stats or a name of your choice. Just run
`git clone https://github.com/https://github.com/stefonarch/Rainode21 /var/www/html/stats`
Note: /var/www/html may be different on your server.
* This frontend should be visible at http://[your-ip-address]/stats/
* Modify settings in /modules/config.php at your needs. 
Note that the IP-address and the port for the RPC  in the file `config.php` have to  match the entries in `RaiBlocks/config.json`
* You might need to additionally install php7.0-curl, i.e. `sudo apt-get install php7.0-curl`

Feel free to change your representative to my RaiBlocks node `rai_1ko3jeb157jc1rkq7piq68f8zkyteut77hn9jjb5y79oxmj7uspo7sf8a6xu` to support further decentralization within the Rai network.






