<?
require_once("../../config/init.php");

require_once 'HTML/QuickForm.php';

$form = new HTML_QuickForm('myform', 'post');
$form->addElement('text', 'email', 'Your email:');
$form->addElement('submit', 'submit', 'Submit');

// Validation rules

$form->addRule('email', 'E-Mail is required', 'required');

// Validation

if ($form->validate()) {
    $form->freeze();
}
$form->display();



?>
