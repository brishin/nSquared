"use strict"

# http://docs.angularjs.org/guide/dev_guide.e2e-testing 
describe "my app", ->
  beforeEach ->
    browser().navigateTo "../../app/index.html"

  describe "Navigation View", ->
    beforeEach ->
      browser().navigateTo "#/"

    it "should render the Navigation toolbar", ->
      expect(element(".toolbar").count()).toBeDefined()

    it "should have categories", ->
      expect(element('li[ng-repeat="category in categories"]').count()).toBeGreaterThan(0)

    it "should have tags", ->
      expect(element('li[ng-repeat="tag in tags"]').count()).toBeGreaterThan(0)


  # describe "view2", ->
  #   beforeEach ->
  #     browser().navigateTo "#/view2"

  #   it "should render view2 when user navigates to /view2", ->
  #     expect(element("[ng-view] p:first").text()).toMatch /partial for view 2/