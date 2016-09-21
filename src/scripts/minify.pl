#!/usr/bin/perl

use strict;
use warnings;
use utf8;
use feature 'say';
use lib '/secure/Common/src/cpan';

use FindBin;
use lib "$FindBin::Bin/lib";
use Getopt::Long;
use Data::Dumper;
use LWP::UserAgent;
use JSON::XS;
use File::Slurp qw/read_file/;
use JSON::XS;
use Mojo::DOM;
use Encode;
use HTML::Packer;
use MongoDB;

binmode(STDOUT, ':encoding(utf8)');

my $ua = LWP::UserAgent->new;

sub trimHtml
{
    my ($html) = @_;
    $html =~ s/onerror="javascript:[^"]+"\s+//g;
    $html =~ s/href="http:\/\/static.wooyun.org\/wooyun\/upload\/[^"]+"/href="javascript:" class="imglink"/g;

	return HTML::Packer::minify(\$html, {});
    # return $html;
}

sub process_single
{
	my ($data) = @_;
	my $result = undef;

	eval {
		my $dom        = Mojo::DOM->new ($data->{html});
		my $bug_descr  = trimHtml($dom->find ('.wybug_description')->[0]->to_string);
		my $bug_detail = trimHtml($dom->find ('.wybug_detail')->[0]->to_string);
		my $bug_poc    = trimHtml($dom->find ('.wybug_poc')->[0]->to_string);

		$result = { 
		    wy_descr  => $bug_descr,
		    wy_detail => $bug_detail,
	    	wy_poc    => $bug_poc,
	    	html      => ''
		};
	};
	return $result;
}

my $mongo = MongoDB->connect();
my $db    = $mongo->get_database('wooyun');
my $col   = $db->get_collection('bugs');

my $bugs  = $col->find();
my $cnt   = 0;
while (my $data = $bugs->next)
{
    say 'Processed ', $cnt, ' entries' if ++ $cnt % 100 == 0;

	if ($data->{html} eq '')
	{
		next;
	}

	say 'UPDATE ', $data->{wooyun_id};
	my $fields = process_single ($data);
	if (not defined $fields) {
		say 'NOT OPEN';
		next;
	}

	$col->update_one({
		_id    => $data->{_id}
	}, {
		'$set' => $fields
	});
}
