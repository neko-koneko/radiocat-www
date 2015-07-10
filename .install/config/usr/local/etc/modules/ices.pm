# At least ices_get_next must be defined. And, like all perl modules, it
# must return 1 at the end.

use strict;
use MP3::Info;

# Full path to the song file at the head of the playlist
my $np_file;


# Function called to initialize your python environment.
# Should return 1 if ok, and 0 if something went wrong.

sub ices_init {
	print "Perl subsystem Initializing:\n";
	return 1;
}

# Function called to shutdown your python enviroment.
# Return 1 if ok, 0 if something went wrong.
sub ices_shutdown {
	print "Perl subsystem shutting down:\n";
}

# Function called to get the next filename to stream. 
# Should return a string.
sub ices_get_next {
        print "Perl subsystem quering for new track:\n";
        #chomp $music[2];
        #$num=`/bin/ls /usr/local/etc/*.mp3 | /usr/bin/wc -l`;
        #@music=`/bin/ls -1 /usr/local/etc/*.mp3`;
        #chomp $num;
        #$play=int(rand($num)); $playnum=$play+1;
        #chomp $music[$play];
        #print "SELECTED FILE: $music[$play] ($playnum of $num) \n";
        #return $music[$play];

        $np_file = `php /usr/local/etc/modules/nextsong.php`;
        print "SELECTED FILE: $np_file \n";
        return $np_file; 

#	print "Perl subsystem quering for new track:\n";
#	return "/usr/local/etc/19 Stahlhammer - Der Mann Mit Dem Koks.mp3";
        # return "/usr/local/etc/01 Rammstein - Halleluja.mp3"; 
}

# If defined, the return value is used for title streaming (metadata)
sub ices_get_metadata {
	print "LOAD METADATA\n";
        print "file name = $np_file\n";

	my $tag = get_mp3tag($np_file);
	
	my $album = $tag->{ALBUM};
	my $artist = $tag->{ARTIST};
	my $title =  $tag->{TITLE};
	my $metadata = "$artist"." - "."$title";

	if (! $album eq ""){
	      $metadata = $metadata." ("."$album".")";
	  }
	
	print "TAG INFO: $metadata\n";

  return $metadata;
}

# Function used to put the current line number of
# the playlist in the cue file. If you don't care
# about cue files, just return any integer.
sub ices_get_lineno {
	return 1;
}

return 1;