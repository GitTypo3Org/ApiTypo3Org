<?php

require 'Packages/Libraries/autoload.php';

$template = 'Resources/template.html';
$output = file_get_contents($template);

$contentFile = 'index.md';
$contentRaw = file_get_contents($contentFile);

$parser = new Parsedown();
$content = $parser->text($contentRaw);

$output = str_replace('###CONTENT###', $content, $output);
$output = str_replace('../', '/Resources/', $output);

print $output;
