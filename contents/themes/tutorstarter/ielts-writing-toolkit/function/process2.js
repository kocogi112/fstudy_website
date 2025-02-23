async function processEssay(i) {
    let userEssay = document.getElementById(`question-${i}-input`).value;
    const sentences = userEssay.split(/[.!?]\s+/);
    let sampleEssay = quizData.questions[i].sample_essay;
    sampleEssay = sampleEssay.replace(/<br>/g, '\n');

    let currentQuestion = quizData.questions[i].question;
    let currentPart = quizData.questions[i].part;
    let currentIDQuestion = quizData.questions[i].id_question;

    console.log("Processing essay...");

    /*try {
        
        const response = await fetch('http://localhost:3000/check-answer', {
            
        //const response = await fetch('https://fstudy-v2.onrender.com/check-answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ question: currentQuestion, answer: userEssay, sample: sampleEssay, part: currentPart, idquestion: currentIDQuestion  })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        let DataSaveTask = document.getElementById(`data-save-task-${i+1}`);
        //let DataSaveTask2 = document.getElementById("data-save-task2");

        // Convert the object to a string and format it with indentation
        DataSaveTask.innerHTML += `<pre>${JSON.stringify(data, null, 2)}</pre>`;

        console.log("Checking overall:", data.overall);
        //console.log("Linking Word Check:", data.linking_words);

        //console.log("Result:", data.result);
        //console.log("Grammar Result:", data.grammar_result);
        //console.log("Vocabulary Range:", data.vocabulary_range);
        //console.log("Linking Word Check:", data.linking_words);
        console.log("Data checking:", data.common_numbers);
        // Display vocabulary range analysis
        //console.log("Words that increase:", data.vocabulary_range.increase.words_found);
        //console.log("Words that decrease:", data.vocabulary_range.decrease.words_found);
        //console.log("Check Structure:", data.structureOverall);
        //console.log("Part: ", data.type);

        
        simple_sentences_count = data.analyze_sentence.Simple.count;
        complex_sentences_count = data.analyze_sentence.Complex.count;
        compound_sentences_count = data.analyze_sentence.Compound.count;

        console.log(simple_sentences_count, complex_sentences_count, compound_sentences_count)

        position_simple_sentences = data.analyze_sentence.Simple.positions;
        position_complex_sentences = data.analyze_sentence.Complex.positions;
        position_compound_sentences = data.analyze_sentence.Compound.positions;
        simple_sentences = data.analyze_sentence.Simple.content;
        complex_sentences = data.analyze_sentence.Complex.content;
        compound_sentences = data.analyze_sentence.Compound.content;
        console.log("All data",data)
        console.log("Analysis sentences:", data.analyze_sentence);
        
        console.log("Checking overall:", data.check_overall);
       // console.log("Result:", data.result);
       // console.log("Linking Word Check:", data.linking_words);
        //console.log("Grammar Result:", data.grammar_result);
        //console.log("Vocabulary Range:", data.vocabulary_range);


        // lấy biến đã gọi ở index để trả về cho submittest xử lý

        const suggestions = data.checkGrammarSpelling.suggestions; // Access the suggestions array directly
        const spellingGrammarErrorArray = suggestions.map(suggestion => suggestion.wrongWord);
        spelling_grammar_error_essay = spellingGrammarErrorArray.join(', ');
        console.log("Grammar array erorr", spelling_grammar_error_essay)


        spelling_grammar_error_count = data.checkGrammarSpelling.total_errors_count || 0;
        length_essay = data.check_overall.word_count;
        console.log("Your length essay",length_essay)

        sentence_count = data.check_overall.sentence_count;
        paragraph_essay = data.check_overall.paragraph_count;

        
        total_linking_word_count = data.linking_words_count;
        console.log("Nb Linking words found new",total_linking_word_count )



        let linking_word_array_essay = data.found_linking_words;
        //let linkingWords = Object.keys(linkingWordArray);
        //linking_word_array_essay = linkingWords.join(', ');
        let linkingWords = linking_word_array_essay.map(linkingWords => linkingWords.word);
        linking_word_to_accumulate = linkingWords.join(', ');
        //console.log("Linking words found new",linking_word_to_accumulate )
        unique_linking_word_count = data.uniqueLinkingWordsCount;
        count_common_number = data.findDataNumber.count;
        

        type_of_essay = data.type_result;
        structure_info = data.structureOverall;

        point_for_intro_cheking_part_1_essay = data.similarity;
        point_for_second_paragraph_cheking_part_1_essay = data.secondSimilarity;


        position_introduction_task_1 = data.structureOverall.intro.wordFound ? data.structureOverall.intro.wordPosition : '0';
        position_overall_task_1 = data.structureOverall.overall.wordFound ? data.structureOverall.overall.wordPosition : '0';
        position_body_task_1 = data.structureOverall.detail.wordFound ? data.structureOverall.detail.wordPosition : '0';
        
        console.log(`Intro: ${position_introduction_task_1}, Overall: ${position_overall_task_1}, Body: ${position_body_task_1}`)

        let increaseWordArray = data.increase.uniqueWords;
        let wordsIncrease = Object.keys(increaseWordArray);
        increase_word_array = wordsIncrease.join(', ');
        increase_word_count = data.increase.wordCount;
        unique_increase_word_count = Object.keys(data.increase.uniqueWords).length;
        


        let decreaseWordArray = data.decrease.uniqueWords;
        let wordsDecrease = Object.keys(decreaseWordArray);
        decrease_word_array = wordsDecrease.join(', ');
        decrease_word_count = data.decrease.wordCount;
        unique_decrease_word_count = Object.keys(data.decrease.uniqueWords).length;



        let unchangeWordArray = data.unchange.uniqueWords;
        let wordsUnchange = Object.keys(unchangeWordArray);
        unchange_word_array = wordsUnchange.join(', ');
        unchange_word_count = data.unchange.wordCount;
        unique_unchange_word_count = Object.keys(data.unchange.uniqueWords).length;


        let goodVerbWordArray = data.goodVerbs.uniqueWords;
        let wordsGoodVerb = Object.keys(goodVerbWordArray);
        goodVerb_word_array = wordsGoodVerb.join(', ');
        goodVerb_word_count = data.goodVerbs.wordCount;
        unique_goodVerb_word_count = Object.keys(data.goodVerbs.uniqueWords).length;



        well_adjective_and_adverb_word_array = data.wellAdjectivesAdverbs.uniqueWords;
        well_adjective_and_adverb_word_count = data.wellAdjectivesAdverbs.wordCount;
        unique_well_adjective_and_adverb_word_count = Object.keys(data.wellAdjectivesAdverbs.uniqueWords).length;



        adjective_and_adverb_word_array = data.adjectivesAdverbs.uniqueWords;
        adjective_and_adverb_word_count = data.adjectivesAdverbs.wordCount;
        unique_adjective_and_adverb_word_count = Object.keys(data.adjectivesAdverbs.uniqueWords).length;



        
        //console.log(`Vị trí intro: ${position_introduction_task_1}`)
        //console.log(`Vị trí overall: ${position_overall_task_1}`)
        //console.log(`Vị trí body: ${position_body_task_1}`)
        // Check structure (intro, overview, detail)
        let introFound = false;
        let overviewFound = false;
        let detailFound = false;

        sentences.forEach((sentence, index) => {
            if (sentence.length === 0) return;

            if (!introFound && /intro/i.test(sentence)) {
                introFound = true;
                console.log(`Intro found in sentence ${index + 1}: "${sentence}"`);
            } else if (introFound && !overviewFound && /overview/i.test(sentence)) {
                overviewFound = true;
                console.log(`Overview found in sentence ${index + 1}: "${sentence}"`);
            } else if (introFound && overviewFound && /detail/i.test(sentence)) {
                detailFound = true;
                console.log(`Detail found in sentence ${index + 1}: "${sentence}"`);
            }
        });

        if (introFound && overviewFound && detailFound) {
            console.log(`Great structure for question ${i + 1}`);
        } else {
            console.error(`Error: Incorrect sentence structure for question ${i + 1}`);
        }

        // Highlight grammar errors
        let userEssayElement = document.getElementById(`userEssayCheck-${i + 1}`);
        
        let modifiedUserEssayText = userEssay;
        let offsetAdjustment = 0;

     

        if (Array.isArray(data.checkGrammarSpelling.suggestions)) {
            data.checkGrammarSpelling.suggestions.forEach(error => {
                let errorText = error.wrongWord;
                let errorOffset = error.offset + offsetAdjustment;
                let errorLength = error.length;
        
                // Create a unique ID for the span
                let uniqueId = 'grammarError_' + new Date().getTime() + Math.random().toString(36).substr(2, 9);
        
                // Generate the suggestions HTML
                let suggestionsHTML = '';
                if (Array.isArray(error.replacements) && error.replacements.length > 0) {
                    error.replacements.forEach(suggestion => {
                        suggestionsHTML += `${suggestion}, `;
                    });
                } else {
                    suggestionsHTML = '<p>No suggestions available</p>';
                }
        
                // Tooltip content with buttons and suggestions
                let tooltipText = `<span class="tooltiptext"> ${suggestionsHTML}</span>`;
        
                // Highlighted text with the tooltip and unique ID
                let highlightedText = `<span id="${uniqueId}" class="tooltip" style="color:red;">${errorText}${tooltipText}</span>`;
        
                // Insert the highlighted text into the modified essay text
                modifiedUserEssayText = modifiedUserEssayText.slice(0, errorOffset) + highlightedText + modifiedUserEssayText.slice(errorOffset + errorLength);
        
                offsetAdjustment += highlightedText.length - errorLength;
        
                // Add event listeners for the tooltip functionality after DOM has been updated
                setTimeout(() => {
                    let span = document.getElementById(uniqueId);
                    let tooltipElement = span.querySelector('.tooltiptext');
                    
                    // Toggle the tooltip visibility on click
                    span.addEventListener('click', function(event) {
                        tooltipElement.style.visibility = (tooltipElement.style.visibility === 'visible') ? 'hidden' : 'visible';
                        tooltipElement.style.opacity = (tooltipElement.style.opacity === '1') ? '0' : '1';
                        event.stopPropagation();
                    });
        
                    
        
                    // Close tooltip when clicking outside of it
                    document.addEventListener('click', function(event) {
                        if (!span.contains(event.target)) {
                            tooltipElement.style.visibility = 'hidden';
                            tooltipElement.style.opacity = '0';
                        }
                    });
                }, 0);
            });
        }
        
        


        //let linking_word_array_essay = data.linking_words.words_found;
        if (Array.isArray(linking_word_array_essay)) {
            linking_word_array_essay.forEach(linkingWordEntry => {
                // Assume each entry is an object or array where the first element or a key `word` contains the actual linking word
                let linkingWord = linkingWordEntry[0] || linkingWordEntry.word || linkingWordEntry; // Adjust based on the actual structure

                let searchIndex = 0;
                let wordLength = linkingWord.length;

                while ((searchIndex = modifiedUserEssayText.toLowerCase().indexOf(linkingWord.toLowerCase(), searchIndex)) !== -1) {
                    let uniqueId = 'linkingWord_' + new Date().getTime() + Math.random().toString(36).substr(2, 9);
                    let highlightedText = `<span id="${uniqueId}" style="color:blue;">${modifiedUserEssayText.substring(searchIndex, searchIndex + wordLength)}</span>`;
                    
                    modifiedUserEssayText =
                        modifiedUserEssayText.slice(0, searchIndex) +
                        highlightedText +
                        modifiedUserEssayText.slice(searchIndex + wordLength);

                    searchIndex += highlightedText.length; // Move past the highlighted word
                }
            });
        }

        //let well_adjective_and_adverb_word_array = data.vocabulary_range.well_adjective_and_adverb.words_found;
        if (Array.isArray(well_adjective_and_adverb_word_array)) {
            well_adjective_and_adverb_word_array.forEach(wellAdjandAdvWordEntry => {
                // Assume each entry is an object or array where the first element or a key `word` contains the actual linking word
                let wellAdjandAdvWord = wellAdjandAdvWordEntry[0] || wellAdjandAdvWordEntry.word || wellAdjandAdvWordEntry; // Adjust based on the actual structure

                let searchIndex = 0;
                let wordLength = wellAdjandAdvWord.length;

                while ((searchIndex = modifiedUserEssayText.toLowerCase().indexOf(wellAdjandAdvWord.toLowerCase(), searchIndex)) !== -1) {
                    let uniqueId = 'wellAdjandAdvWord_' + new Date().getTime() + Math.random().toString(36).substr(2, 9);
                    let highlightedText = `<span id="${uniqueId}" style="color:green; font-weight:bold">${modifiedUserEssayText.substring(searchIndex, searchIndex + wordLength)}</span>`;
                    
                    modifiedUserEssayText =
                        modifiedUserEssayText.slice(0, searchIndex) +
                        highlightedText +
                        modifiedUserEssayText.slice(searchIndex + wordLength);

                    searchIndex += highlightedText.length; // Move past the highlighted word
                }
            });
        }


        modifiedUserEssayText = modifiedUserEssayText.replace(/\n/g, '<br>');

        // Update the user essay element with the final modified text
       userEssayElement.innerHTML = modifiedUserEssayText;
       
    } catch (error) {
        console.error('Error during fetch:', error);
    }
}
*/


    /*console.log("url process",url_end_point);
    console.log(all_time_use);
    console.log("Type gate", type_gate);
    console.log(`Today use for ${now_end_point}`,today_use);
    console.log("Now end point use: ", now_end_point);
    console.log("Next end point for update: ", next_end_point_for_update);*/
    // Gửi yêu cầu POST đến endpoint của WordPress

    /*fetch(url_end_point, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ question: currentQuestion, answer: userEssay, part: currentPart})

    })*/
    
    fetch(`${siteUrl}/api/public/test/v1/ielts/writing/`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ question: currentQuestion, answer: userEssay, part: currentPart, sample: sampleEssay, idquestion: currentIDQuestion})

    })

    .then(response => response.json())
    .then(data => {
        // Hiển thị kết quả từ API
    })
    .catch(error => {
        console.error('Error:', error);
    });

}

