$(document).ready(function() {
    // When a key is released in the search input field with class 'search'
    $(".search").keyup(function () {
        // Get the value entered in the search input
        var searchTerm = $(".search").val();
        // Select all table rows within '.results tbody'
        var listItem = $('.results tbody').children('tr');
        // Replace spaces in searchTerm with "'):containsi('"
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('");

        // Extend jQuery with case-insensitive ':containsi' selector
        $.extend($.expr[':'], {
            'containsi': function(elem, i, match, array) {
                // Case-insensitive search in element's text content
                return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        // Hide rows that do not match the search term
        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e) {
            $(this).attr('visible','false');
        });

        // Show rows that match the search term
        $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e) {
            $(this).attr('visible','true');
        });

        // Count the number of visible rows
        var jobCount = $('.results tbody tr[visible="true"]').length;
        // Display the count in the counter element
        $('.counter').text(jobCount + ' item');

        // Show 'no-result' message if no rows match the search term
        if (jobCount == '0') {
            $('.no-result').show();
        } else {
            $('.no-result').hide();
        }
    });
});
