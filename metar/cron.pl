require "/home2/zobartcc/public_html/metar/METAR.pm";
use LWP::UserAgent;
use DBI;

$dbh = DBI->connect("DBI:mysql:zobartcc_zob","zobartcc_zob","zDcD0\@UiZSQT");

if (!$dbh) { print "Error!!\n"; exit; }

$dbh->do("DELETE FROM `weather`");
$dbh->do("TRUNCATE TABLE `weather`");

$ua = new LWP::UserAgent;

@stations = ("KDTW","KCLE","KPIT","KBUF","KROC","KLAN","KMFD","KTOL");

foreach $station (@stations)
{
my $req = new HTTP::Request GET =>
  "http://weather.noaa.gov/cgi-bin/mgetmetar.pl?cccc=$station";

my $response = $ua->request($req);

if (!$response->is_success) {

    print $response->error_as_HTML;
    my $err_msg = $response->error_as_HTML;
    warn "$err_msg\n\n";
    die "$!";
}

    # Yep, get the data and find the METAR.

    my $data;
    $data = $response->as_string;
    $data =~ s/\n//go;                          # remove newlines
    $data =~ m/([A-Z]+\s\d+Z.*?)</go;       # find the METAR string
    my $metar = $1;
    my $m = new Geo::METAR;
    $m->metar ($metar);
#print $m->dump;

$alt = $m->ALT;
if ($m->wind eq "00000KT") { $wind = "Calm"; }
else { $wind = $m->WIND_DIR_DEG . '@' . $m->WIND_KTS;
	if ($m->WIND_GUST_KTS) { $wind .= "G" . $m->WIND_GUST_KTS; } }
if ($m->VISIBILITY =~ /^(\d+) (\d+)\/(\d+)$/) { $vis = $1 + ($2 / $3); }
else { $vis = $m->VISIBILITY; }

$layer = 99999;
foreach $piece (@{$m->sky})
{
	if ($piece =~ /^BKN(\d\d\d)/ || $piece =~ /^OVC(\d\d\d)/) {
		$layer = int($1 * 100);
		last;
	}
}

	if ($vis < 3 || $layer < 1000) {
		$fr = "IFR";
	} elsif ($vis < 5 || $layer < 3000) {
		$fr = "MVFR";
	} else {
		$fr = "VFR";
	}
	$dbh->do("INSERT INTO `weather` VALUES('$station','$fr','$wind','$alt','$metar')");
}
exit;

