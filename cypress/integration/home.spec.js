describe("Home route", function() {
  beforeEach(function() {
    cy.visit("http://localhost/");
  });
  it("Latest Results are displayed", function() {
    cy.contains(/Latest Results/i);
    cy.get(".LiveResultsTable").should("exist");
    cy.contains(/See all results/i);
  });
  it("Highlihts are displayed", function() {
    cy.contains(/HIGHLIGHTS/i);
    cy.get(".Highlight").should("exist");
    cy.get(".Highlight img").should("exist");
    cy.get(".Highlight .Highlight__type").should("exist");
    cy.get(".Highlight .Highlight__title").should("exist");
    cy.get(".Highlight .Highlight__description").should("exist");
  });
  it("Nearest Track module is displayed", function() {
    cy.contains(/VISIT THE DOGS/i);
    cy.contains("Enter postcode, city").click();
    cy.get(".NearestTrackLocate__search input").type("test");
    cy.contains("Find track").click();
    //should move to racecourses page
    cy.contains(/Our Racecourses/i);
  });
  it("Social Media Posts are displayed", function() {
    cy.contains(/SOCIAL FEED/i);
    cy.get(".SocialMediaPost").should("exist");
  });
});
