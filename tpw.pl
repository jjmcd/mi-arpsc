#!/usr/bin/perl -w
use MIME::Base64;
print "Content-Type: text/html\n\n";
print "<html>\n  <head>\n    <title>Test</title>\n  </head>\n";
print "  <body>\n";

print "    <p>New test text here.</p>\n";

open(SOURCE, "<", "/home/virtual/site367/fst/home/wb8rcr/dbp.txt")
    or die "Couldn't open dbp for reading $!\n";
@lines = <SOURCE>;
print "<ul>\n";
print "<li>Open database '" . $lines[2] . "'</li>\n";
print "<li>with usercode '" . decode_base64($lines[1]) . "'</li>\n";
print "<li>and password '" . decode_base64($lines[0]) . "'</li>\n";
print "</ul>\n";

print "    <p>More text here.</p>\n";

print "  </body>\n</html>\n";
exit;
