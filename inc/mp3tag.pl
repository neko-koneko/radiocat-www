#!/usr/bin/perl
use strict;
use MP3::Info;
use JSON::XS;

$MP3::Info::try_harder = 100;

    my $filename = $ARGV[0];

    if (-f $filename){
	    my $tag0 = get_mp3tag($filename,1);
	    my $tag1 = get_mp3tag($filename,0,1);
	    my $tag2 = get_mp3tag($filename,0,2);
	    my $info = get_mp3info($filename);
	    my %re =('filename' => $filename, 'tag0' => $tag0, 'tag1' => $tag1, 'tag2' => $tag2, 'info' => $info,'message' => $@);
	    my $re = \%re;
	    my $json_text = JSON::XS->new->utf8->encode ( $re );
	    print  $json_text;
   	}else{
	    my %re =('filename' => $filename, 'error' => 'file not found');
	    my $re = \%re;
	    my $json_text = JSON::XS->new->utf8->encode ( $re );
	    print  $json_text;
    	}

