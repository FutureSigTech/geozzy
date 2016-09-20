<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeStory extends Module {

  public $name = 'rtypeStory';
  public $version = 1.0;
  public $rext = array('rextStory', 'rextView', 'rextSocialNetwork','rextComment');

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeStoryController.php',
    'view/RTypeStoryView.php',
    'js/Stories.js',
    'js/collection/StoryCollection.js',
    'js/view/ListStoryView.js',
    'js/view/StoryTemplate.js'
  );

  public $nameLocations = array(
    'es' => 'Historia',
    'en' => 'Story',
    'gl' => 'Historia'
  );


  public function __construct() {
    $this->addUrlPatterns( '#^api/admin/adminStories$#', 'view:StoryAdminAPIView::stories' );
    $this->addUrlPatterns( '#^api/admin/adminStories.json$#', 'view:StoryAdminAPIView::storiesJson' );

    $this->addUrlPatterns( '#^api/story/(.*)#', 'view:StoryAPIView::story' );
    $this->addUrlPatterns( '#^api/doc/story.json#', 'view:StoryAPIView::storyJson' ); // Main swagger JSON
    $this->addUrlPatterns( '#^api/storyList$#', 'view:StoryAPIView::storyList' );
    $this->addUrlPatterns( '#^api/doc/storyList.json$#', 'view:StoryAPIView::storyListJson' ); // Main swagger JSON
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}