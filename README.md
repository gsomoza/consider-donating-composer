# Consider Donating Composer Plugin

This plugin was inspired by [this thread on Twitter](https://twitter.com/blooomca/status/1136939474229682176).

The aim of the project is to provide a common interface for package developers to prompt their users for donations
in a way that feels "natural" for everyone involved.

## Commitment

* Donations will always be optional.
* This project will always remain free and open source.
* We will never collect commission on any donations.

## Usage

### For Package Mantainers

As a package developer, you can prompt your users to donate to your package by doing the following:

1. Setup a landing page where you collect your donations.
2. Require this Composer plugin: `composer require gsomoza/consider-donating`
3. Add the following to your package's `composer.json` file:
```
"extra": {
    "donations": {
        "url": "http://your-donation.com/page"
    }
}
```

That's it! When someone installs your package they will see a message on their terminal, like the following:
```
Your project depends on the generous work of real people.
Please consider donating to the following open-source projects:

    * gabriel/consider-donating

To donate, simply run "composer donate package/name"
```

### For end-users
End-users can follow on-screen instructions to donate, but additionally they can also:

##### Authenticate
Authenticating allows you to keep your donations even if you're working within VM's or Containers, or if you switch 
computers.

## Roadmap

### 1. Implement basic protocol and functionality
Already implemented.

### 2. Donation Validations

The proposed donation validation flow (not yet implemented) would work as follows:

##### Version 1.0
1. The user is redirected to a donation page where they can perform their donation.
2. At the same time, an email is sent to the package maintainer with a link. Once the donation is confirmed, the package
   maintainer can click on that link to confirm the donation within our system.
3. The next time the user uses Composer (with an Internet connection), our plugin will automatically validate the 
   donation took place and cache the validation token locally. They will not see a suggestion to donate to that package 
   anymore.

To be added after release:
* Integrations for the one or two most common donation platforms.
* Allow easily migrating donation tokens (e.g. to a new computer)

##### Version 2:
* Support for team-level donations (useful for companies)
