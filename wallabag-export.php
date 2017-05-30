<?php
require_once 'db-mysql.php';
$db = new db('db', 'wallabag', 'root', 'tTuG04Z5N2', '');

echo '<pre>';
//Get all Tags
$db->setCol('wallabag_tag');
$db->get();
$tags = [];
foreach($db->data as $tag)
{
    $tags[$tag['id']] = ['label' => $tag['label'], 'slug' => $tag['slug']];
}

$entries_export = [];
$db->setCol('wallabag_entry');
$db->get();
$entries = $db->data;
foreach($entries as $entry)
{
  $db->setCol('wallabag_entry_tag');
  $db->data['entry_id'] = $entry['id'];
  $db->get();
  $tags_entry = [];
  foreach($db->data as $tag_entry)
  {
    $tags_entry[] = $tags[$tag_entry['tag_id']]['label'];
  }

  $entries_export[] = [
  'is_archived' => $entry['is_archived'],
  'is_starred' => $entry['is_starred'],
  'tags' => $tags_entry,
  'id' => $entry['id'],
  'title' => $entry['title'],
  'url' => $entry['url'],
  'content' => $entry['content'],
  'created_at' => $entry['created_at'],
  'updated_at' => $entry['updated_at'],
  'annotations' => [],
  'mimetype' => $entry['mimetype'],
  'language' => $entry['language'],
  'reading_time' => $entry['reading_time'],
  'domain_name' => $entry['domain_name'],
  'http_status' => 200
];
}

$entries = json_encode($entries);
if(file_put_contents('wallabag_export.json', $entries))
{
  echo 'Export successfully saved';
} else {
  echo 'Error saving export';
}

//echo $entries;


echo '</pre>';
