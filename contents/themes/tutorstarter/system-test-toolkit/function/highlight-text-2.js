document.addEventListener('DOMContentLoaded', function() {
    // Listen for mouseup event on the document
    document.addEventListener('mouseup', function() {
        // Get the selected text
        var selection = window.getSelection();
        var selectedText = selection.toString();

        // Check if any text is selected
        if (selectedText !== '') {
            // Highlight the selected text
            highlightSelectedText(selection);
        }
    });

    function highlightSelectedText(selection) {
        if (selection.rangeCount > 0) {
            // Get the range of the selected text
            var range = selection.getRangeAt(0);

            // Create a new span element to wrap the selected text
            var span = document.createElement('span');
            span.className = 'tooltip';
            span.style.backgroundColor = 'yellow';  // Default background color

            // Create a unique ID for the span
            var uniqueId = 'myTooltip_' + new Date().getTime();
            span.id = uniqueId;

            // Surround the selected text with the span element
            range.surroundContents(span);

            // Create the tooltip content with buttons
            var tooltipText = document.createElement('span');
            tooltipText.className = 'tooltiptext';
            tooltipText.innerHTML = `
                <image id="highlight-icon-modify" class="delete_highlight" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/remove-highlight.png"></image>
                <image id="highlight-icon-modify" onclick="green_highlight('${uniqueId}')" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/green-highlight.png"></image>
                <image id="highlight-icon-modify" onclick="blue_highlight('${uniqueId}')" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/blue-highlight.png"></image>
            `;

            // Add the tooltip content to the span
            span.appendChild(tooltipText);

            // Add click event listener to the span element
            span.addEventListener('click', function(event) {
                // Toggle the tooltip visibility
                tooltipText.style.visibility = (tooltipText.style.visibility === 'visible') ? 'hidden' : 'visible';
                tooltipText.style.opacity = (tooltipText.style.opacity === '1') ? '0' : '1';

                // Prevent the event from bubbling up to document level
                event.stopPropagation();
            });

            // Add click event listener to the delete button
            tooltipText.querySelector('.delete_highlight').addEventListener('click', function(event) {
                // Create a document fragment to hold the text nodes
                var fragment = document.createDocumentFragment();

                // Move all child nodes except the tooltip to the fragment
                while (span.firstChild && span.firstChild !== tooltipText) {
                    fragment.appendChild(span.firstChild);
                }

                // Replace the span with the content in the fragment
                span.parentNode.replaceChild(fragment, span);

                // Prevent the event from bubbling up to document level
                event.stopPropagation();
            });

            // Close the tooltip when clicking outside of it
            document.addEventListener('click', function(event) {
                if (!span.contains(event.target)) {
                    tooltipText.style.visibility = 'hidden';
                    tooltipText.style.opacity = '0';
                }
            });
        }
    }
});