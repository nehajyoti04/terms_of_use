<?php

/**
 * @file
 * Terms of use module functionality tests.
 */

namespace Drupal\aggregator\Tests;

/**
 * Base class for all terms od use web test cases.
 */
class TOUWebTestCase extends DrupalWebTestCase {

  protected $admin_user;
  protected $node;
  protected $lable;
  protected $lable_link;
  protected $fieldset;
  protected $body;
  protected $xss;

  public function setUp() {
    // String for XSS Test.
    $this->lable = $this->testString();
    $this->lable_link = $this->testString() . ' @link ';
    $this->fieldset = $this->testString();
    $this->body = $this->randomName();

// Enable the terms of use module.
    parent::setUp('terms_of_use');

    $this->admin_user = $this->drupalCreateUser(array('administer site configuration'));
    $this->drupalLogin($this->admin_user);

    // Default terms of use node.
    $this->node = $this->drupalCreateNode(
      array(
        'type' => 'page',
        'body' => array(LANGUAGE_NONE => array(array('value' => $this->body))),
      )
    );
  }

  private function testString() {
    return '<a href="/' . $this->randomString() . '">' . $this->randomString() . '</a><script>alert("' . $this->randomString() . '");</script>';
  }
}

/**
 * Test for all simple settings.
 */
class TOUSettingsTestCase extends TOUWebTestCase {

  public static function getInfo() {
    return array(
    'name' => 'TOU Basic',
    'description' => 'Basic functionality tests.',
    'group' => 'Terms of use',
    );
  }

  public function testTOUBasic() {
    $edit = array(
      'terms_of_use_node_title' => $this->node->title,
      'terms_of_use_checkbox_label' => $this->lable,
      'terms_of_use_fieldset_name' => $this->fieldset,
    );
    $this->drupalPost('admin/config/people/terms_of_use', $edit, t('Save configuration'));
    $this->assertFieldByName('terms_of_use_node_title', $this->node->title, 'Node set.');
    $this->assertFieldByName('terms_of_use_checkbox_label', $this->lable, 'Lable name set.');
    $this->assertFieldByName('terms_of_use_fieldset_name', $this->fieldset, 'Fieldset name set.');

    $this->drupalLogout();
    $this->drupalGet('user/register');
    $this->assertRaw(filter_xss_admin($this->lable), 'Checkbox lable found.');
    $this->assertRaw(filter_xss($this->fieldset), 'Fieldset name found.');
    debug("aaa");
    debug($this->body);
    $this->assertText($this->body, 'Terms node body text found.');


    $this->drupalPost('user/register', array(), t('Create new account'));
    $this->assertRaw(t('!name field is required.', array('!name' => filter_xss($this->lable))), 'Checkbox lable found.');

    $edit = array(
      'name' => $this->randomName(),
      'mail' => $this->randomName() . '@example.com',
      'terms_of_use' => TRUE,
    );
    $this->drupalPost('user/register', $edit, t('Create new account'));
    $this->assertText('Thank you for applying for an account.', 'Account creation successfull.');

    $this->drupalLogin($this->admin_user);

    $edit = array(
      'terms_of_use_checkbox_label' => $this->lable_link,
    );
    $this->drupalPost('admin/config/people/terms_of_use', $edit, t('Save configuration'));
    $this->assertFieldByName('terms_of_use_checkbox_label', $this->lable_link, 'Lable name with @link set.');

    $this->drupalLogout();
    $this->drupalGet('user/register');

    $replaced_lable = str_replace('@link', l($this->node->title, 'node/' . $this->node->nid), filter_xss_admin($this->lable_link));
    $this->assertRaw($replaced_lable, 'Checkbox lable found.');
    $this->assertNoText($this->body, 'Terms node body text not found.');
  }
}
