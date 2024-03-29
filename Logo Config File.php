<?php

echo "Berikan path direktori yang akan di imbas:\n";
$handle = fopen ("php://stdin","r");
$dir = trim(fgets($handle));
if (!is_dir($dir)) throw new \Error( "Ralat!! Direktori yang di berikan tidak wujud!");

echo "Sila pilih jenis fail konfigurasi yang ingin di tulis:\n\t1) Logo(normal)\n\t2) Kecil(ikon)\n";
$line = trim(fgets($handle));

if ($line!=1 && $line!=2) throw new \Error("Ralat!!! Hanya 1 atau 2 sahaja dibenarkan sebagai pilihan($line)");
$type=$line==1?'normal':'small';
$size=$type=='normal'?'logo':'icon';
$record='';

// normal
foreach (new \FilesystemIterator("$dir/$type",FilesystemIterator::SKIP_DOTS) as $entry)
{
    if ($entry->isFile() && $entry->getExtension()=='png')
    {
        preg_match('#^(\d+)(?:\s(.+))?$#',$entry->getBasename('.png'),$rgx_club);
        if ($rgx_club) $record.="<record from=\"$rgx_club[0]\" to=\"graphics/pictures/club/$rgx_club[1]/$size\"/>\n        ";
    }
}

$xml=<<<XML
<record>
    <!-- resource manager options -->

    <!-- don't preload anything in this folder -->
    <boolean id="preload" value="false"/>

    <!-- turn off auto mapping -->
    <boolean id="amap" value="false"/>

    <!-- picture mappings -->
    <!-- the following XML maps pictures inside this folder into other positions
        in the resource system, which allows this folder to be dropped into any
        place in the graphics folder and still have the game pick up the graphics
        files from the correct places
    -->

    <list id="maps">
        <!-- Generated by FMConfigPHP by ChopCokia -->
        $record
    </list>
</record>
XML;
file_put_contents("$dir/$type/config.xml",$xml);
echo "Fail konfigurasi 'config.xml' telah berjaya di tulis: $dir/$type/";
