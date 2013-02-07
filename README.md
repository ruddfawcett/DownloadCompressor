DownloadCompressor
==================

A useless little tool written in PHP which allows you to download a file to your server from a url, compress it, then generate an automatic download link to the .zip with a random file name.  Built to familiarize myself with PHP's ZipArchive.

Files are downloaded to the `temp`  directory where they are then zipped, deleted and moved to the `files` directory.
