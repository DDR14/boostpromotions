<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
    <head>
        <title><?php echo $this->fetch('title'); ?></title>
    </head>
    <body>        
        <table style="width: 600px; font-family: Tahoma; border: 1px solid #c2c2c2; background: #f9f9f9; padding: 10px">
            <tr>
                <td>
                    <?= $this->Html->image("company-logo.png", array("style" => "width:250px", "fullBase" => true)); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $this->fetch('content'); ?>
                </td>
            </tr>
        </table>
    <br /><br />
    <table style="width: 600px; font-family: Tahoma; border: 1px solid #c2c2c2; background: #f9f9f9; padding: 10px">
        <tr>
            <td>
                <small>This email address was given to us by you or by one of our customers. If you did not signup for an account, or feel that you have received this email in error, please send an email to <a href="mailto:ustomerservice@boostpromotions.com " target="_top">customerservice@boostpromotions.com </a>
                    <br /><br />
                    This email is sent in accordance with the US CAN-SPAM Law in effect 01/01/2004. Removal requests can be sent to this address and will be honored and respected.</small>
            </td>
        </tr>
    </table>
    <br />
    <table style="width: 630px; font-family: Tahoma; border: 1px solid #c2c2c2; background: #f9f9f9; padding: 10px; text-align: center">
        <tr>
            <td>
                <small>Copyright (c) 2016 <a href="https://boostpromotions.com">BoostPromotions.com.</a></small>
            </td>
        </tr>
    </table>
</body>
</html>
