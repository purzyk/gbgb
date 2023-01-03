describe("User area", function() {
  beforeEach(function() {
    cy.visit("http://localhost/");
  });
  it("Logs into the website as user", function() {
    cy.get(".Item__myKennel:visible")
      .first()
      .click();
    cy.contains("Login").click();
    cy.get("#your-email")
      .click()
      .type("cypress@example.com");
    cy.get("#your-password")
      .click()
      .type("pass");
    cy.get("button")
      .contains(/Log in/i)
      .click();
  });
});
