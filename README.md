# READ ME - Environmental Syllabi Filter

In March, 2011, Dan Cohen [published](http://www.dancohen.org/2011/03/30/a-million-syllabi/) a database of over one million syllabi that he had discovered using Google's API. Dan's database includes the syllabus' URL, title, snippet, and some more; it does not include the syllabus' text itself.

In this current project, I loaded Dan's SQL file into mysql and named it "syllabi."  Then I wrote a little and clunky PHP script — getSyllabi.php — that lets you filter it for keywords in the title. It produces a JSON file of the ones that are still online. (To judge whether a file is online, it gets the header and looks for "200" in it.)

To change the keywords the script looks for, just change $query.

The script reads a little JSON file  — login.json — that contains the username and password for your database. Edit that file so it contains your info, or just plop your username and password straight into the PHP file, of course.

syllabi-ecology.json contains metadata about 444 syllabi that have "ecology" in the title and that are still available online; there are a total of about 2,400 syllabi in that meet that condition in the database, but most are no longer available.

syllabi-environment-nodupes.json contains metadata about 275 syllabi with the stem "environment" in the title that are still online. That's out of 2,808 total in the database. The entries in this file contain no duplicates that are in the other json file.

FWIW, this script is offered as Open Source software. Do what you want with it, except try not to laugh. I'm an amateur. By posting it, I do not intend to imply that it works or that it will have no unintended nasty consequences. Use at your own risk.

David Weinberger  
November 12, 2016  
david@weinberger.org

