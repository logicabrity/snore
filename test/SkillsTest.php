<?php
require_once 'config.php';

class SkillsTest extends PHPUnit_Framework_TestCase
{
  public function languagesProvider() {
    return array(
      array('en'),
      array('fr'),
      array('de')
    );
  }

  /**
   * @dataProvider languagesProvider
   */
  public function testSkillListIsReturnedAsArray($lang) {
    $skills = new Skills($lang);
    $this->assertTrue(is_array($skills->flat()));
  }

  /**
   * @dataProvider languagesProvider
   */
  public function testSkillListContains74Skills($lang) {
    $skills = new Skills($lang);
    $this->assertEquals(count($skills->flat()), 74);
  }

  /**
   * @dataProvider languagesProvider
   */
  public function testSkillListIsMadeOfStrings($lang) {
    $skills = new Skills($lang);
    $this->assertContainsOnly('string', $skills->flat());
  }

  /**
   * @dataProvider languagesProvider
   */
  public function testNestedSkillListHas6Categories($lang) {
    $skills = new Skills($lang);
    $this->assertEquals(count($skills->nested()), 6);
  }

  /**
   * @dataProvider languagesProvider
   */
  public function testNestedSkillListContains74Skills($lang) {
    $skills = new Skills($lang);
    // 74 skills + 6 categories = 80 array elements
    $this->assertEquals(count($skills->nested(), COUNT_RECURSIVE), 80);
  }
}
?>
