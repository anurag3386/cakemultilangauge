<?php
  /**
   * Validate language translations
   *
   * Usage: validate.php?lang={dk|du|sp}
   */

require_once(ROOTPATH.'/include/lang/en.php');
require_once(ROOTPATH.'/include/lang/dk.php');
require_once(ROOTPATH.'/include/lang/du.php');
require_once(ROOTPATH.'/include/lang/sp.php');

echo "<table>";
/* header */
echo "<tr>";
echo "<td>English</td>";
echo "<td>Danish</td>";
echo "<td>Dutch</td>";
echo "<td>Spanish</td>";
echo "</tr>";
/* close */
echo "</table>";

?>
