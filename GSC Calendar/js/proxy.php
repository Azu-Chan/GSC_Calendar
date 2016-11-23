<?php
$url = $_GET["rssURL"];
if ($url != ""){
$monRSSDoc = new DOMDocument();
if ($monRSSDoc->load($url)){
 header('Content-Type: text/xml');
echo $monRSSDoc->saveXML();
}else{
echo "erreur de lecture";
}
}else{
echo "URL vide";
}
?>