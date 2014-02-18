<?
# File: example.php
# BEWARE: This is a UTF-8 file.

# ##########################################################################################
# Include of "My Translation":
#include_once('my_translation.class.php'); # Main class to doing translations.
# ##########################################################################################

# Simple connection to DB:
#$resource=mysql_connect('localhost', 'openvet', 'openvet');
#mysql_select_db('test_openvet');


# ##########################################################################################
# Begin of "My Translation" zone.
$mytrans = new My_Translation($resource); # Pass DB connection to My Translation class.
$mytrans -> SetLanguage(); # Set application language.
# echo "Language code defined: ".$mytrans -> LanguageID."<BR>"; # DEBUG
$mytrans -> GetTranslations(100); # Get all translations needed for this page ID.
# End of "My Translation" zone.
# ##########################################################################################


# ##########################################################################################
# General code section:

# To check your different languages defined you must only change language in your browser settings.
echo "<P>";
echo "Simple example (if you have defined tags in your own language you must see them here):<BR>";
echo "<P>";
echo "Client said: ".$mytrans -> Tag(1)."<BR>";
echo "Waiter replied: ".$mytrans -> Tag(2)."<BR>";

echo "<P><BR>";

echo "Example without tags defined:<BR><BR>";
echo "Client said: ".$mytrans -> Tag(11)."<BR>";
echo "Waiter replied: ".$mytrans -> Tag(12)."<BR>";

?>
