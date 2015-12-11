# Mapbender3

Mapbender3 is a web based geoportal framework.

The [official site](http://mapbender3.org/?q=en) contains [documentation](http://mapbender3.org/?q=en/documentation) and [installation information](http://doc.mapbender3.org/en/book/installation.html) ([also available in German](http://doc.mapbender3.org/de/book/installation.html)).

To install Mapbender3 from this Git-repository, please read the guide of the [Git-based installation](http://doc.mapbender3.org/en/book/installation/installation_git.html) ([in German](http://doc.mapbender3.org/de/book/installation/installation_git.html)).


## Branches

The current version of Mapbender is based on the [release/3.0.5 branch](https://github.com/mapbender/mapbender-starter/tree/release/3.0.5). The master branch will reflect the changes of the [current releases](https://github.com/mapbender/mapbender-starter/releases) while the develop branch might contain new features.



## Components

Our code is maintained using git and hosted at Github. We split up our code into several parts:

1. mapbender-starter: The starter project you are using right now. This provides a complete application to play with and build upon.
2. [mapbender](https://github.com/mapbender/mapbender/tree/release/3.0.5): The CoreBundle contains all basic functionality, including base classes and interfaces for the Mapbender3 API usable by Mapbender and third-party bundles.
3. [FOM](https://github.com/mapbender/fom/tree/release/3.0.5): User and rights management.
4. [OWSProxy3](https://github.com/mapbender/owsproxy3/tree/release/3.0.5): OWSProxy3 is a transparent Buzz-based proxy that uses cURL for connection to web resources via/without a proxy server.
5. mapquery: Mapbender uses MapQuery as it's jQuery/OpenLayers wrapper. We maintain our own clone.


## Issues

Please report issues at the [Mapbender repository here at Github](https://github.com/mapbender/mapbender/issues).


## Other downloads

Prebuild Tarballs and Zip files (where all sumbodules and Symfony bundles are integrated) are available at our [Download page](http://mapbender3.org/download).


## Mapbender3 demo & sandbox

Wanna see Mapbender3 live? A demo installation is available at http://demo.mapbender3.org/.


## Follow us on Twitter

You can follow Mapbender3 at [Twitter](https://twitter.com/mapbender)
