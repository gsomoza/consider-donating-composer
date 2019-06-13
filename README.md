# Consider Donating Composer Plugin

This plugin was inspired by [this thread on Twitter](https://twitter.com/blooomca/status/1136939474229682176).

The aim of the project is to provide a common interface for package developers to prompt their users for donations
in a way that feels "natural" for everyone involved.

### Commitment

* Donations will always be optional.
* This project will always remain free and open source.
* We will never collect commission on any donations.

### Roadmap

1. Implement basic protocol and functionality (DONE).
2. Implement a simple service to track donations and avoid being re-prompted.
3. Explore the possibility of aggregating donations on a per-project basis.

# Usage

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
Your project depends on the generous work of real people and organizations.
Please consider donating to the following open-source projects:

    * gabriel/consider-donating

To donate, simply run "composer donate package/name"
```
