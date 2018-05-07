# Mapbender

Mapbender is a web based geoportal framework.

The [official site](http://mapbender.org/?q=en) contains [documentation](http://mapbender.org/?q=en/documentation) and [installation information](http://doc.mapbender.org/en/book/installation.html) ([also available in German](http://doc.mapbender.org/de/book/installation.html)).

To install Mapbender from this Git-repository, please read the guide of the [Git-based installation](http://doc.mapbender.org/en/book/installation/installation_git.html) ([in German](http://doc.mapbender.org/de/book/installation/installation_git.html)).


## Components

Our code is maintained using git and hosted at Github. We split up our code into several parts:

1. mapbender-starter: The starter project you are using right now. This provides a complete application to play with and build upon.
2. [mapbender](https://github.com/mapbender/mapbender/tree/release/3.0.6): The CoreBundle contains all basic functionality, including base classes and interfaces for the Mapbender API usable by Mapbender and third-party bundles.
3. [FOM](https://github.com/mapbender/fom/tree/release/3.0.6): User and rights management.
4. [OWSProxy3](https://github.com/mapbender/owsproxy3/tree/release/3.0.6): OWSProxy3 is a transparent Buzz-based proxy that uses cURL for connection to web resources via/without a proxy server.
5. mapquery: Mapbender uses MapQuery as its jQuery/OpenLayers wrapper. We maintain our own clone.


## Issues

Please report issues at the [Mapbender repository here at Github](https://github.com/mapbender/mapbender/issues).


## Other downloads

Pre-built Tarballs and Zip files (where all subbodules and Symfony bundles are integrated) are available at our [Download page](http://mapbender.org/download).


## Mapbender demo & sandbox

Wanna see Mapbender live? A demo installation is available at http://demo.mapbender.org/.


## Follow us on Twitter

You can follow Mapbender at [Twitter](https://twitter.com/mapbender).
