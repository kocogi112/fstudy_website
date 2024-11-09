function processEssay() {
    
    //let userEssay = document.getElementById(`question-${i}-input`).value;
    
    let userEssay = tasks.task1.description;
    const sentences = userEssay.split(/[.!?]\s+/);
       
    /*console.log("Checking overall:", data.overall);
    console.log("Linking Word Check:", data.linking_words);

    console.log("Result:", data.result);
    console.log("Grammar Result:", data.grammar_result);
    console.log("Vocabulary Range:", data.vocabulary_range);
    console.log("Linking Word Check:", data.linking_words);
    console.log("Data checking:", data.common_numbers);
    // Display vocabulary range analysis
    console.log("Words that increase:", data.vocabulary_range.increase.words_found);
    console.log("Words that decrease:", data.vocabulary_range.decrease.words_found);
    console.log("Check Structure:", data.structureOverall);
    console.log("Part: ", data.type);*/

    
    let simple_sentences_count_task_1 = dataTask1Essay.analyze_sentence.Simple.count;
    let complex_sentences_count_task_1 = dataTask1Essay.analyze_sentence.Complex.count;
    let compound_sentences_count_task_1 = dataTask1Essay.analyze_sentence.Compound.count;

    console.log(simple_sentences_count_task_1, complex_sentences_count_task_1, compound_sentences_count_task_1)

    let position_simple_sentences_task_1 = dataTask1Essay.analyze_sentence.Simple.positions;
    let position_complex_sentences_task_1 = dataTask1Essay.analyze_sentence.Complex.positions;
    let position_compound_sentences_task_1 = dataTask1Essay.analyze_sentence.Compound.positions;
    let simple_sentences_task_1 = dataTask1Essay.analyze_sentence.Simple.content;
    let complex_sentences_task_1 = dataTask1Essay.analyze_sentence.Complex.content;
    let compound_sentences_task_1 = dataTask1Essay.analyze_sentence.Compound.content;
    //console.log("All data",task)
    console.log("Analysis sentences:", dataTask1Essay.analyze_sentence);
    
    console.log("Checking overall:", dataTask1Essay.check_overall);
   /* console.log("Result:", data.result);
    console.log("Linking Word Check:", data.linking_words);
    console.log("Grammar Result:", data.grammar_result);
    console.log("Vocabulary Range:", data.vocabulary_range);*/


    // lấy biến đã gọi ở index để trả về cho submittest xử lý

    const suggestions_task_1 = dataTask1Essay.checkGrammarSpelling.suggestions; // Access the suggestions array directly
    const spellingGrammarErrorArray_task_1 = suggestions_task_1.map(suggestion => suggestion.wrongWord);
    spelling_grammar_error_essay_task_1 = spellingGrammarErrorArray_task_1.join(', ');
    console.log("Grammar array erorr", spelling_grammar_error_essay_task_1)


    spelling_grammar_error_count_task_1 = dataTask1Essay.checkGrammarSpelling.total_errors_count || 0;
    length_essay_task_1 = dataTask1Essay.check_overall.word_count;
    console.log("Your length essay",length_essay_task_1)

    sentence_count_task_1 = dataTask1Essay.check_overall.sentence_count;
    paragraph_essay_task_1 = dataTask1Essay.check_overall.paragraph_count;

    
    total_linking_word_count_task_1 = dataTask1Essay.linking_words_count;
    console.log("Nb Linking words found new",total_linking_word_count_task_1 )



    let linking_word_array_essay_task_1 = dataTask1Essay.found_linking_words;
    //let linkingWords = Object.keys(linkingWordArray);
    //linking_word_array_essay = linkingWords.join(', ');
    let linkingWords_task_1 = linking_word_array_essay_task_1.map(linkingWords_task_1 => linkingWords_task_1.word);
    linking_word_to_accumulate_task_1 = linkingWords_task_1.join(', ');
    //console.log("Linking words found new",linking_word_to_accumulate )
    unique_linking_word_count_task_1 = dataTask1Essay.uniqueLinkingWordsCount;
    count_common_number_task_1 = dataTask1Essay.findDataNumber.count;
    

    let type_of_essay_task_1 = dataTask1Essay.type_result;
    let structure_info_task_1 = dataTask1Essay.structureOverall;

    let point_for_intro_cheking_part_1_essay = dataTask1Essay.similarity;
    let point_for_second_paragraph_cheking_part_1_essay = dataTask1Essay.secondSimilarity;


    let position_introduction_task_1 = dataTask1Essay.structureOverall.intro.wordFound ? dataTask1Essay.structureOverall.intro.wordPosition : '0';
    let position_overall_task_1 = dataTask1Essay.structureOverall.overall.wordFound ? dataTask1Essay.structureOverall.overall.wordPosition : '0';
    let position_body_task_1 = dataTask1Essay.structureOverall.detail.wordFound ? dataTask1Essay.structureOverall.detail.wordPosition : '0';
    
    console.log(`Intro: ${position_introduction_task_1}, Overall: ${position_overall_task_1}, Body: ${position_body_task_1}`)

    let increaseWordArray_task_1 = dataTask1Essay.increase.uniqueWords;
    let wordsIncrease_task_1 = Object.keys(increaseWordArray_task_1);
    increase_word_array_task_1 = wordsIncrease_task_1.join(', ');
    increase_word_count_task_1 = dataTask1Essay.increase.wordCount;
    unique_increase_word_count_task_1 = Object.keys(dataTask1Essay.increase.uniqueWords).length;
    


    let decreaseWordArray_task_1 = dataTask1Essay.decrease.uniqueWords;
    let wordsDecrease_task_1 = Object.keys(decreaseWordArray_task_1);
    decrease_word_array_task_1 = wordsDecrease_task_1.join(', ');
    decrease_word_count_task_1 = dataTask1Essay.decrease.wordCount;
    unique_decrease_word_count_task_1 = Object.keys(dataTask1Essay.decrease.uniqueWords).length;



    let unchangeWordArray_task_1 = dataTask1Essay.unchange.uniqueWords;
    let wordsUnchange_task_1 = Object.keys(unchangeWordArray_task_1);
    unchange_word_array_task_1 = wordsUnchange_task_1.join(', ');
    unchange_word_count_task_1 = dataTask1Essay.unchange.wordCount;
    unique_unchange_word_count_task_1 = Object.keys(dataTask1Essay.unchange.uniqueWords).length;


    let goodVerbWordArray_task_1 = dataTask1Essay.goodVerbs.uniqueWords;
    let wordsGoodVerb_task_1 = Object.keys(goodVerbWordArray_task_1);
    goodVerb_word_array_task_1 = wordsGoodVerb_task_1.join(', ');
    goodVerb_word_count_task_1 = dataTask1Essay.goodVerbs.wordCount;
    unique_goodVerb_word_count_task_1 = Object.keys(dataTask1Essay.goodVerbs.uniqueWords).length;



    well_adjective_and_adverb_word_array_task_1 = dataTask1Essay.wellAdjectivesAdverbs.uniqueWords;
    well_adjective_and_adverb_word_count_task_1 = dataTask1Essay.wellAdjectivesAdverbs.wordCount;
    unique_well_adjective_and_adverb_word_count_task_1 = Object.keys(dataTask1Essay.wellAdjectivesAdverbs.uniqueWords).length;



    adjective_and_adverb_word_array_task_1 = dataTask1Essay.adjectivesAdverbs.uniqueWords;
    adjective_and_adverb_word_count_task_1 = dataTask1Essay.adjectivesAdverbs.wordCount;
    unique_adjective_and_adverb_word_count_task_1 = Object.keys(dataTask1Essay.adjectivesAdverbs.uniqueWords).length;



    ///
/*







ADD CHO TASK 2











*/



let simple_sentences_count_task_2 = dataTask2Essay.analyze_sentence.Simple.count;
    let complex_sentences_count_task_2 = dataTask2Essay.analyze_sentence.Complex.count;
    let compound_sentences_count_task_2 = dataTask2Essay.analyze_sentence.Compound.count;

    console.log(simple_sentences_count_task_2, complex_sentences_count_task_2, compound_sentences_count_task_2)

    let position_simple_sentences_task_2 = dataTask2Essay.analyze_sentence.Simple.positions;
    let position_complex_sentences_task_2 = dataTask2Essay.analyze_sentence.Complex.positions;
    let position_compound_sentences_task_2 = dataTask2Essay.analyze_sentence.Compound.positions;
    let simple_sentences_task_2 = dataTask2Essay.analyze_sentence.Simple.content;
    let complex_sentences_task_2 = dataTask2Essay.analyze_sentence.Complex.content;
    let compound_sentences_task_2 = dataTask2Essay.analyze_sentence.Compound.content;
    //console.log("All data",task)
    console.log("Analysis sentences:", dataTask2Essay.analyze_sentence);
    
    console.log("Checking overall:", dataTask2Essay.check_overall);
   /* console.log("Result:", data.result);
    console.log("Linking Word Check:", data.linking_words);
    console.log("Grammar Result:", data.grammar_result);
    console.log("Vocabulary Range:", data.vocabulary_range);*/


    // lấy biến đã gọi ở index để trả về cho submittest xử lý

    const suggestions_task_2 = dataTask2Essay.checkGrammarSpelling.suggestions; // Access the suggestions array directly
    const spellingGrammarErrorArray_task_2 = suggestions_task_2.map(suggestion => suggestion.wrongWord);
    spelling_grammar_error_essay_task_2 = spellingGrammarErrorArray_task_2.join(', ');
    console.log("Grammar array erorr", spelling_grammar_error_essay_task_2)


    spelling_grammar_error_count_task_2 = dataTask2Essay.checkGrammarSpelling.total_errors_count || 0;
    length_essay_task_2 = dataTask2Essay.check_overall.word_count;
    console.log("Your length essay",length_essay_task_2)

    sentence_count_task_2 = dataTask2Essay.check_overall.sentence_count;
    paragraph_essay_task_2 = dataTask2Essay.check_overall.paragraph_count;

    
    total_linking_word_count_task_2 = dataTask2Essay.linking_words_count;
    console.log("Nb Linking words found new",total_linking_word_count_task_2 )



    let linking_word_array_essay_task_2 = dataTask2Essay.found_linking_words;
    //let linkingWords = Object.keys(linkingWordArray);
    //linking_word_array_essay = linkingWords.join(', ');
    let linkingWords_task_2 = linking_word_array_essay_task_2.map(linkingWords_task_2 => linkingWords_task_2.word);
    linking_word_to_accumulate_task_2 = linkingWords_task_2.join(', ');
    //console.log("Linking words found new",linking_word_to_accumulate )
    unique_linking_word_count_task_2 = dataTask2Essay.uniqueLinkingWordsCount;
    count_common_number_task_2 = dataTask2Essay.findDataNumber.count;
    

    let type_of_essay_task_2 = dataTask2Essay.type_result;
    let structure_info_task_2 = dataTask2Essay.structureOverall;

    let point_for_intro_cheking_part_2_essay = dataTask2Essay.similarity;
    let point_for_second_paragraph_cheking_part_2_essay = dataTask2Essay.secondSimilarity;


    let position_introduction_task_2 = dataTask2Essay.structureOverall.intro.wordFound ? dataTask2Essay.structureOverall.intro.wordPosition : '0';
    let position_overall_task_2 = dataTask2Essay.structureOverall.overall.wordFound ? dataTask2Essay.structureOverall.overall.wordPosition : '0';
    let position_body_task_2 = dataTask2Essay.structureOverall.detail.wordFound ? dataTask2Essay.structureOverall.detail.wordPosition : '0';
    
    console.log(`Intro: ${position_introduction_task_2}, Overall: ${position_overall_task_2}, Body: ${position_body_task_2}`)

    let increaseWordArray_task_2 = dataTask2Essay.increase.uniqueWords;
    let wordsIncrease_task_2 = Object.keys(increaseWordArray_task_2);
    increase_word_array_task_2 = wordsIncrease_task_2.join(', ');
    increase_word_count_task_2 = dataTask2Essay.increase.wordCount;
    unique_increase_word_count_task_2 = Object.keys(dataTask2Essay.increase.uniqueWords).length;
    


    let decreaseWordArray_task_2 = dataTask2Essay.decrease.uniqueWords;
    let wordsDecrease_task_2 = Object.keys(decreaseWordArray_task_2);
    decrease_word_array_task_2 = wordsDecrease_task_2.join(', ');
    decrease_word_count_task_2 = dataTask2Essay.decrease.wordCount;
    unique_decrease_word_count_task_2 = Object.keys(dataTask2Essay.decrease.uniqueWords).length;



    let unchangeWordArray_task_2 = dataTask2Essay.unchange.uniqueWords;
    let wordsUnchange_task_2 = Object.keys(unchangeWordArray_task_2);
    unchange_word_array_task_2 = wordsUnchange_task_2.join(', ');
    unchange_word_count_task_2 = dataTask2Essay.unchange.wordCount;
    unique_unchange_word_count_task_2 = Object.keys(dataTask2Essay.unchange.uniqueWords).length;


    let goodVerbWordArray_task_2 = dataTask2Essay.goodVerbs.uniqueWords;
    let wordsGoodVerb_task_2 = Object.keys(goodVerbWordArray_task_2);
    goodVerb_word_array_task_2 = wordsGoodVerb_task_2.join(', ');
    goodVerb_word_count_task_2 = dataTask2Essay.goodVerbs.wordCount;
    unique_goodVerb_word_count_task_2 = Object.keys(dataTask2Essay.goodVerbs.uniqueWords).length;



    well_adjective_and_adverb_word_array_task_2 = dataTask2Essay.wellAdjectivesAdverbs.uniqueWords;
    well_adjective_and_adverb_word_count_task_2 = dataTask2Essay.wellAdjectivesAdverbs.wordCount;
    unique_well_adjective_and_adverb_word_count_task_2 = Object.keys(dataTask2Essay.wellAdjectivesAdverbs.uniqueWords).length;



    adjective_and_adverb_word_array_task_2 = dataTask2Essay.adjectivesAdverbs.uniqueWords;
    adjective_and_adverb_word_count_task_2 = dataTask2Essay.adjectivesAdverbs.wordCount;
    unique_adjective_and_adverb_word_count_task_2 = Object.keys(dataTask2Essay.adjectivesAdverbs.uniqueWords).length;




    ///
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
       // console.log(`Great structure for question ${i + 1}`);
    } else {
       // console.error(`Error: Incorrect sentence structure for question ${i + 1}`);
    }

    // Highlight grammar errors
    /*let userEssayElement = document.getElementById(`userEssayCheck-${i + 1}`);
    
    let modifiedUserEssayText = userEssay;
    let offsetAdjustment = 0;

 

    if (Array.isArray(dataTask1Essay.checkGrammarSpelling.suggestions)) {
        dataTask1Essay.checkGrammarSpelling.suggestions.forEach(error => {
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
   */

      

   if(count_common_number_task_1 >= 2 && length_essay_task_1 > 100){
    relation_point_essay_and_question_task_1 = (point_for_second_paragraph_cheking_part_1_essay + point_for_intro_cheking_part_1_essay)/2
}else{
    relation_point_essay_and_question_task_1 = 0;
}

if(count_common_number_task_2 >= 2 && length_essay_task_2 > 100){
    relation_point_essay_and_question_task_2 = (point_for_second_paragraph_cheking_part_2_essay + point_for_intro_cheking_part_2_essay)/2
}else{
    relation_point_essay_and_question_task_2 = 0;
}




   if (CurrentEssayTask1Final == 1 && WordCountTask1Final > 30)
    {
        // 

        if (WordCountTask1Final < 130){
            task_achievement1_1_comment = `Độ dài bài viết của bạn quá ngắn. Đối với Task 1 độ dài tối thiểu là 150 từ. Bạn nên viết trong khoảng 160 - 190 từ đối với dạng bài có 1 biểu đồ và khoảng 170 - 210 từ đối với dạng bài nhiều biểu đồ (mixed chart/graph). Việc viết thiếu sẽ ảnh hưởng rất nhiều đến kết quả của bạn và thông thường sẽ nhận được < 5.0 band `
        
        }
        else{
            if (WordCountTask1Final >= 130 && WordCountTask1Final < 150 ){
                task_achievement1_1_comment = `Có vẻ như độ dài của bài viết bạn chưa đạt yêu cầu. Bạn nên cố gắng để độ dài Task 1 kéo dài lên > 150 từ để cải thiện điểm cho phần Task Achievement. Nếu bạn chưa đủ độ dài do vấn đề thời gian, hãy cải thiện bằng cách luyện tập nhiều, tập trung cao độ cho bài viết của mình. Đối với task 1 bạn nên dùng nhiều nhất 20 phút để hoàn thiện (5 phút lên dàn ý, 10 phút viết bài và 5 phút dành cho việc soát lỗi). `
            }
            else if(WordCountTask1Final >= 150 && WordCountTask1Final < 160 && type_of_essay_task_1 != 'Mixed'){
                task_achievement1_1_comment =`Độ dài bài viết đã đạt yêu cầu ! (> 150 từ). Tuy vậy, bạn nên mở rộng ra từ 160 - 180 từ nhé, để bài viết đầy đủ và giám khảo sẽ nhận thấy bài viết của bạn chỉnh chu và đủ ý. Về độ dài bạn vẫn sẽ được 75 - 80% số điểm cho phần độ dài nhưng bạn nên lưu ý thêm nhé ^^. `
            }
            else if(WordCountTask1Final >= 150 && WordCountTask1Final < 170 && type_of_essay_task_1 == 'Mixed'){
                task_achievement1_1_comment = `Độ dài bài viết đủ yêu cầu (> 150 từ). Nhưng đối với dạng bài mixed graph/chart như này, bạn nên viết trong khoảng 170 - 200 từ để đạt tối đa điểm cho độ dài nhé. Về độ dài bạn vẫn sẽ được 60 - 75% số điểm cho phần độ dài nhưng bạn nên lưu ý thêm nhé ^^. `;
            }
            else if(WordCountTask1Final >= 170 && WordCountTask1Final < 225 && type_of_essay_task_1 == 'Mixed'){
                task_achievement1_1_comment = `Tuyệt vời ! Độ dài bài viết của bạn cho task 1(dạng mixed) là hoàn hảo rồi. Bạn sẽ đạt tối đa điểm cho phần độ dài trong bài thi lần này lẫn bài thi thật (real exam) khi bạn thi Ielts. Hãy tiếp tục phát huy nhé ^^. `;
            }
            else if(WordCountTask1Final >= 160 && WordCountTask1Final < 225){
                task_achievement1_1_comment = `Tuyệt vời ! Độ dài bài viết của bạn cho task 1 là hoàn hảo rồi. Bạn sẽ đạt tối đa điểm cho phần độ dài trong bài thi lần này lẫn bài thi thật (real exam) khi bạn thi Ielts. Hãy tiếp tục phát huy nhé ^^. `
            }
            else if(WordCountTask1Final >= 225 && WordCountTask1Final < 250){
                task_achievement1_1_comment = `Độ dài bài viết của bạn là quá dài. Việc này sẽ ảnh hưởng tới thời gian làm bài của bạn là rất lớn. Bạn nên nhớ đây mới chỉ là Writing Task 1 và ở bài thi thật bạn còn task 2 (sẽ chiếm phần lớn thời gian và điểm). Hãy giới hạn bài viết của bạn lại trong khoảng 160 - 180 từ để đạt điểm tối đa của phần length(độ dài)`
            }
            else if (WordCountTask1Final >= 250){
                task_achievement1_1_comment = `No comment -.- Sao bạn viết dài thế !? Bạn đang trêu tôi hay sao, bạn nên viết trong khoảng 160 - 180 từ thôi để đạt yêu cầu đề bài và cũng vừa thời gian làm bài. Bạn sẽ KHÔNG đạt điểm cho phần độ dài !`
            }

            if(paragraph_essay_task_1 < 3){
                task_achievement1_1_comment += `Ngoài ra, bạn nên chia bài làm của bạn thành 3 - 4 phần (đoạn). Phần 1: Introduction, phần 2: Overall, phần 3: Detail information (phân tích các dữ liệu trong đề), phần 4 (optional/ không bắt buộc): Tổng kết lại bài làm`
            }
            else if(paragraph_essay_task_1 == 3 || paragraph_essay_task_1 == 4){
                task_achievement1_1_comment += `Ngoài ra, bạn chia bố cục bài làm hợp lý rồi (3 - 4 đoạn) để phù hợp logic, cấu trúc bài làm và phù hợp với các tiêu chí khác như Coherence and Cohesion`
            }
            else{
                task_achievement1_1_comment += `Bạn đang chia bố cục không hợp lý cho lắm. Thông thường ở task 1, bạn nên chia làm 3 - 4 phần tương ứng Introduction, Overall, Detail Information, Summary(không bắt buộc). Việc chia thành nhiều đoạn sẽ gây ra nhiễu thông tin và ảnh hưởng tính mạch lạc và gắn kết của bài làm `
            }
        }

        if (count_common_number_task_1 == 0){
            task_achievement1_2_comment = `Bạn chưa sử dụng bất cứ thông tin và số liệu nào có trong bài. Điều này có nghĩa là bạn đang không viết đúng và lạc đề hoàn toàn. Bạn nên nhớ rằng writing task 1 sẽ kiểm tra khả năng sử dụng các dữ kiện trong các chart/ process/ table/ map nên bạn cần phân tích các số liệu có trong đó. Chính vì vậy điểm cho tiêu chí task achievement của bạn sẽ rất thấp nếu không bao gồm các dữ liệu / thông tin đề bài cho` 
        }
        else{
            if (count_common_number_task_1 >= 1 && count_common_number_task_1 <= 3){
                task_achievement1_2_comment = `Bạn cần phải đề cập mọi thông tin QUAN TRỌNG có trong hình vẽ, việc chỉ bao gồm ${count_common_number_task_1} thông tin (bao gồm các đối tượng trong bài và các dữ liệu) là tương đối ít và nhiều khả năng bạn đã thiếu nhiều thông tin quan trọng`;
            }
            else if(count_common_number_task_1 >= 4 && count_common_number_task_1 <= 7){
                task_achievement1_2_comment= `Bạn đã tương đối sử dụng đủ các thông tin có trong bài, bao gồm các số liệu và các đối tượng cần được so sánh. Cụ thể là ${count_common_number_task_1} lần áp dụng số liệu vào để phân tích và so sánh. Bạn nên tiếp tục phát huy điều này nhé ! `
            }
            else if (count_common_number_task_1 >= 8 && count_common_number_task_1 <= 11){
                task_achievement1_2_comment= `Bạn đã sử dụng đầy đủ các thông tin có trong bài và sử dụng nó chi tiết. Điều này sẽ giúp bạn đạt điểm cao cho tiêu chí Task Achievement đấy. Hãy giữ vững phong độ nhé ! `
            }
            else{
                task_achievement1_2_comment = `Bạn đang hơi lạm dụng thêm các thông tin có trong hình ảnh và đề bài. Việc sử dụng đầy đủ thông tin là tốt và sẽ tăng điểm cho bạn tiêu chí Task Achievment nhưng bạn nên chọn lọc cô đọng, chọn lọc những thông tin tiêu biểu và quan trọng nhất chứ đừng lạm dụng quá vì nó sẽ không chú trọng trong việc phân tích, so sánh các thông tin đó.`
            }
        }

        if(relation_point_essay_and_question_task_1 < 0.9){
            if(point_for_intro_cheking_part_1_essay >= 0.9 && point_for_second_paragraph_cheking_part_1_essay < 0.9){
                task_achievement1_3_comment += ` Hệ thống tự động nhận xét về bài của bạn như sau: Phần giới thiệu (Introduction - đoạn 1) của bạn đã đủ thông tin nhưng phần Overall- đoạn 2 của bạn đang chưa tốt. Câu Overall Writing Task 1 là câu quan trọng nhất của câu Writing Task 1, đó là một câu chứa tóm tắt ý tưởng tổng quát. Nói cho dễ hiểu, câu OVERALL chính là cái mà khi nhìn sơ qua biểu đồ, bảng biểu hoặc hình vẽ những đặc điểm nào mà mình thấy đầu tiên, nổi bật nhất chính là câu overall nhé. Tức là đập vào mắt mình những điểm nào (thường là 2 điểm) nổi bật nhất sẽ lấy đó làm câu overall `
            }
            else if(point_for_intro_cheking_part_1_essay < 0.9 && point_for_second_paragraph_cheking_part_1_essay <= 0.9){
                task_achievement1_3_comment += ` Hệ thống tự động nhận xét về bài của bạn như sau: Phần Introduction - đoạn 1 của bạn viết chưa tốt, tuy vậy phần 2 (Overall của bạn viết ổn rồi nhé). Phần mở bài trong IELTS Writing Task 1 thường chỉ gói gọn trong một câu, nhằm mục đích giới thiệu về biểu đồ hoặc bảng số liệu được mô tả. Cách nhanh và đơn giản nhất để viết phần này là diễn đạt lại đề bài theo văn phong của chính bạn, sử dụng các từ đồng nghĩa. `
            }else{
                task_achievement1_3_comment +=` Sau khi hệ thống check bài của bạn thì dường như bài viết của bạn không đề cập đến những gì đề bài yêu cầu, bài viết của bạn đang lạc đề hoàn toàn.<br> Phần mở bài trong IELTS Writing Task 1 thường chỉ gói gọn trong một câu, nhằm mục đích giới thiệu về biểu đồ hoặc bảng số liệu được mô tả. Cách nhanh và đơn giản nhất để viết phần này là diễn đạt lại đề bài theo văn phong của chính bạn, sử dụng các từ đồng nghĩa. Câu Overall Writing Task 1 là câu quan trọng nhất của câu Writing Task 1, đó là một câu chứa tóm tắt ý tưởng tổng quát. Nói cho dễ hiểu, câu OVERALL chính là cái mà khi nhìn sơ qua biểu đồ, bảng biểu hoặc hình vẽ những đặc điểm nào mà mình thấy đầu tiên, nổi bật nhất chính là câu overall nhé. Tức là đập vào mắt mình những điểm nào (thường là 2 điểm) nổi bật nhất sẽ lấy đó làm câu overall  `
            }
        }
        else{
            if(point_for_intro_cheking_part_1_essay > 0.95){
                task_achievement1_3_comment +=`  Có vẻ như introduction - câu mở đầu của bạn viết không ổn rồi !. Thông thường introduction sẽ phải được paraphrase bằng cách sử dụng các từ đồng nghĩa hoặc sử dụng cấu trúc câu khác nhé. Câu mở đầu (introduction) của bạn có vẻ đã bao gồm ý chính của bài tuy nhiên chưa paraphrase dẫn đến việc không được đánh giá cao về khả năng sử dụng từ, sử dụng cấu trúc câu ! `
            }
            else{
                task_achievement1_3_comment += `Câu mở đầu (Introduction) và câu tóm gọn (Overall) của bạn viết tốt rồi, đã bao gồm các ý chính/ quan trọng mà đề bài này yêu cầu. Đồng thời bạn cũng đã paraphrase lại và sử dụng hợp lý nó. Hãy tiếp tục phát huy điều này nhé ^^. ` 
            }
        }
    


        if ((position_introduction_task_1 < position_overall_task_1) && (position_overall_task_1 < position_body_task_1)){
            coherenceandcohesion1_1_comment = ` Đoạn văn này đã được viết đúng theo trình tự introduction -> overall -> body rồi. Hãy nhớ viết đúng trình tự này ở mọi bài writing task 1 để phù hợp logic và tạo nên sự liên kết nhất nhé`

        }
        else{
            coherenceandcohesion1_1_comment = ` Trình tự đoạn văn của bạn đang có vấn đề. 1 bài wiritng task 1 cần được trình bày theo trình tự introduction -> overall rồi đến body. <br> - Introduction nên là 1 câu ngắn gọn ở đoạn 1 bao quát cả bài làm (1 tip nhỏ cho bạn là bạn nên paraphrase lại đề bài)<br>- Overall (hay còn là overview) cần được trình bài ở đoạn số 2 và chúng ta cần nhìn vào “bức tranh tổng thể”, chứ đừng nhìn vào ” số liệu chi tiết”. Bạn cũng nên sử dụng 1 số cụm từ để bắt đầu đoạn overall như: Overall, it is obvious/apparent/clear that… (Nhìn chung, rõ ràng là…); It can easily be noticed/seen from the graph/table that… (Có thể dễ dàng nhận thấy/nhìn thấy từ biểu đồ, bảng rằng…); As is shown/illustrated by the graph…(Như được trình bày trong biểu đồ…)<br>- Body (Detail Information): Bạn có thể dùng đoạn 3 hoặc đoạn 4(nếu muốn chia body làm 2 đoạn) để viết phần này. Body sẽ bắt đầu phân tích chi tiết các số liệu, đưa ra so sánh các số liệu đó. Ở phần này, giám khảo sẽ đánh giá bạn qua các phân tích, sử dụng các thông tin hợp lý nhưng vẫn chi tiết và đầy đủ thông tin. `
        }

        if(point_for_intro_cheking_part_1_essay < 0.9){
            coherenceandcohesion1_2_comment =`Phần introduction của bạn có vẻ chưa hợp lý (Lạc đề/ Chưa đủ thông tin cần thiết). Ngoài ra introduction nên là 1 câu và được pharaphrase lại từ đề bài. Ví dụ nhé:<br> Đề bài: Đề bài: “The line graph below shows the consumption of fish and different kinds of meat in a European country between 1979 and 2004”. <br> Introduction sẽ là: The line graph illustrates the consumption of fish and different kinds of meat in a European country between 1979 and 2004. ` 
        }
        else{
            if(point_for_intro_cheking_part_1_essay >= 0.9 && point_for_intro_cheking_part_1_essay <= 0.94){
                coherenceandcohesion1_2_comment =`Phần introduction này đã ổn rồi nhé. Nó đã bao gồm các ý chính của bài và bạn cũng làm tốt trong việc paraphrase nó ! ` 
            }
            else{
                coherenceandcohesion1_2_comment = ` Ôi bạn ơi ! Có vẻ bạn chưa paraphrase lại đề bài hoặc bạn chỉ paraphrase mỗi 1 từ/cụm từ nhỏ. Bạn nên thử thay đổi cấu trúc câu xem. Ví dụ nhé:<br> Đề bài: Đề bài: “The line graph below shows the consumption of fish and different kinds of meat in a European country between 1979 and 2004”. <br> Introduction sẽ là: The line graph illustrates the consumption of fish and different kinds of meat in a European country between 1979 and 2004.`
            }
        }

        if (point_for_second_paragraph_cheking_part_1_essay < 0.9){
            coherenceandcohesion1_2_comment += `<br><br> - Tiếp đến phần overall(Thông thường ở đoạn 2): Bạn chưa làm tốt điều đó. Overall của bạn chưa chỉ ra những điểm đặc biệt / quan trọng của yêu cầu. Overall nên viết ở đoạn 2 và nên viết trong khoảng 2 - 3 câu thể hiện quan điểm của bạn về key informaion. main trends (Những thay đổi rõ rệt nhất). Ta xét ví dụ sau: <br> <p style ="font-style: italic">Overall, petrol and oil are the primary power sources in this country throughout the period shown, while the least used power sources are nuclear and renewable energy. It is also noticeable that the consumption of petrol and oil and coal experiences the greatest increases over the period given.</p><br> Như bạn thấy ở mẫu overview này, nó đã chỉ rõ cao nhất/thấp nhất hay dao động đáng chú ý nhất ("petrol and oil are the primary power sources in this country throughout the period shown, while the least used power sources are nuclear and renewable energy." - Petrol and oil cao nhất, Nuclear, solar/wind và hydropower là thấp nhất.) và xu hướng chung của biểu đồ ("It is also noticeable that the consumption of petrol and oil and coal experiences the greatest increases over the period given." - Petrol and oi và coal là những đường nổi bật nhất vì nó có sự tăng trưởng mạnh nhất.) `
        }
        else{
            coherenceandcohesion1_2_comment +=`<br><br> - Tiếp đến phần overall(Thông thường ở đoạn 2): Phần này bạn viết ổn rồi ! Đã bao gồm các main trend/ key information và cũng đã paraphrase. Làm tốt nha ! ` 

        }

        if(total_linking_word_count_task_1 == 0){ //xem lại: linking words:   https://yourielts.net/prepare-for-ielts/ielts-writing/academic-task-1/ielts-writing-task-1-linking-words-for-describing-a-graph
            coherenceandcohesion1_3_comment = `Bạn không sử dụng bất cứ linking words (Từ nối) nào cả. Việc này sẽ làm mất tính liên kết, liên quan giữa các câu, các đoạn với nhau và ảnh hưởng đến tiêu chí cohesion and coherence. Một số linking words phổ biến cần phải được sử dụng như: and, also, but,...`;
        }
        else{
            //if(total_linking_word_count > 0 && total_linking_word_count < 6){
             if(total_linking_word_count_task_1 > 0){
                coherenceandcohesion1_3_comment = `Các linking words đã được bạn sử dụng trong bài viết là: ${linking_word_to_accumulate_task_1}. Bạn đang sử dụng quá ít linking words, hãy sử dụng nhiều hơn để tăng tính liên kết cho tiêu chí cohestion and coherence. Nhớ là nên sử dụng nhiều và phân bổ đều ở các đoạn nha. Bạn có thể tham khảo các từ nối sau: <br> Dùng để liên kết các câu/ thêm thông tin : furthermore, additionally, in addition to, also, moreover, and, as well as,... <br> Để thêm thời gian: during, while, until, before, afterward, in the end, at the same time, meanwhile, subsequently, simultaneously,...<br> Để liệt kê: firstly, secondly, thirdly, fourthly, lastly,... <br> Dùng để cung cấp ví dụ: for instance, for example, to cite an example, to illustrate, namely <br> Dùng để nhấn mạnh: obviously, particularly, in particular, especially, specifically, clearly,... <br> Dùng để chỉ hậu quả: as a result, therefore, thus, consequently, for this reason, so, hence,...` 
            }
            

            if(unique_linking_word_count_task_1 <= 2){ //check
                coherenceandcohesion1_3_comment += ` Các linking words đã được bạn sử dụng trong bài viết là: ${linking_word_to_accumulate_task_1}. Bạn có sử dụng linking words để nối các câu lại với nhau. Tuy nhiên các từ nối ấy vẫn không đa dạng. Bạn nên sử dụng ít nhất 6 từ nối trong bài và ít nhất 3 từ nối trong đó là có sự khác biệt. Bạn có thể tham khảo các từ nối sau: <br> Dùng để liên kết các câu/ thêm thông tin : furthermore, additionally, in addition to, also, moreover, and, as well as,... <br> Để thêm thời gian: during, while, until, before, afterward, in the end, at the same time, meanwhile, subsequently, simultaneously,...<br> Để liệt kê: firstly, secondly, thirdly, fourthly, lastly,... <br> Dùng để cung cấp ví dụ: for instance, for example, to cite an example, to illustrate, namely <br> Dùng để nhấn mạnh: obviously, particularly, in particular, especially, specifically, clearly,... <br> Dùng để chỉ hậu quả: as a result, therefore, thus, consequently, for this reason, so, hence,...`
            }
            else { //check
                coherenceandcohesion1_3_comment = `Các linking words đã được bạn sử dụng trong bài viết là: ${linking_word_to_accumulate_task_1}. Số lượng linking words cho task 1 như này là đã ổn và có vẻ đầy đủ, phòng phú nha. Bạn có thể bổ sung thêm kiến thúc về 1 số từ nối như additionally, in addition to, also, moreover, and, as well as, ` 
            }
        }
        
        if(increase_word_count_task_1 > 1){
            if(unique_increase_word_count_task_1 <= 2){
                lexical_resource1_1_comment = `Một số động từ để chỉ sự tăng trường đã được bạn sử dụng như " ${increase_word_array_task_1} " Tuy vậy, bạn cũng nên sử dụng thêm các động từ để chỉ sự tăng trưởng khác như grow, increase, rise, climb, go up,... để thể hiện khả năng linh hoạt trong cách sử dụng từ vựng của bạn` 
            }
            else{
                lexical_resource1_1_comment = `Bạn đã sử dụng các động từ/ cụm từ thể hiện xu hướng tăng trưởng khi so sánh giữa nhiều đối tượng khác nhau như " ${increase_word_array_task_1}" và những cụm từ đó cũng linh hoạt trong cách sử dụng`
            }
        }else{
            lexical_resource1_1_comment = `Trong bài viết, bạn chưa sử dụng bất cứ từ ngữ/ động từ nào để thể hiện sự tăng trưởng. Do vậy bạn không làm rõ được quan điểm của mình khi so sánh các đối tượng trong bài. Hãy sử dụng các cụm từ chỉ sự tăng trưởng như increase, soar, rise, grow up,... nhiều hơn nhé`
        }


        if(decrease_word_count_task_1 > 1){
            if(unique_decrease_word_count_task_1 <= 2){
                lexical_resource1_1_comment += ` Tương tự vậy ta xét đến các động từ/ cụm từ chỉ sự suy giảm. Trong bài viết bạn đã sử dụng " ${decrease_word_array_task_1}" , tuy vậy vẫn còn hạn chế trong cách sử dụng. Bạn nên sử dụng thêm các động từ để chỉ sự giảm như downturn, drop, collapse, decline, decrease, fall, reduce,... ` 
            }
            else{
                lexical_resource1_1_comment += `Tương tự vậy ta xét đến các động từ/ cụm từ chỉ sự suy giảm. Bạn đã sử dụng các động từ/ cụm từ thể hiện xu hướng giảm khi so sánh giữa nhiều đối tượng khác nhau như " ${decrease_word_array_task_1} "và những cụm từ đó cũng linh hoạt trong cách sử dụng`
            }
        }else{
            lexical_resource1_1_comment += `Tương tự vậy ta xét đến các động từ/ cụm từ chỉ sự suy giảm. Bạn chưa sử dụng bất cứ từ ngữ/ động từ nào để thể hiện sự giảm sút. Do vậy bạn không làm rõ được quan điểm của mình khi so sánh các đối tượng trong bài. Hãy sử dụng các cụm từ chỉ sự giảm như downturn, drop, collapse, decline, decrease, fall, reduce,..`
        }
        
        if(unchange_word_count_task_1 > 0 && unchange_word_count_task_1 <= 5){
            lexical_resource1_1 += 1;
            if(unique_unchange_word_count_task_1 < 2){
                lexical_resource1_1_comment += ` Bạn nên sử dụng thêm các động từ hoặc các cụm từ để chỉ sự không giảm (không đổi) - xu hướng ổn định khác như: a leveling off, show stability, plateau, stability, keep constant, stabilize,... thay vì chỉ sử dụng mỗi ${unchange_word_array_task_1}`
            }
            else{
                lexical_resource1_1_comment += ` Bạn cũng đã sử dụng thêm các động từ/ cụm từ diễn tả xu hướng không đổi, giữ nguyên và cũng sử dụng chúng 1 cách linh hoạt, không trùng lặp như ${unchange_word_array_task_1}`
            }
        }
        else{
            lexical_resource1_1_comment += `Không nên sử dụng quá nhiều cụm từ so sánh vì nó sẽ làm sao nhãng việc phân tích. Cụ thể ở bài viết này, khi mô tả xu hướng ổn định, không thay đổi, bạn đã sử dụng ${unchange_word_count_task_1} cụm/động từ: ${unchange_word_array_task_1}. Hãy hạn chế nó nhé, nên nhớ bạn chỉ có 20 phút để viết task 1 và bạn nên giới hạn trong 190 từ !` 
        }



        if (goodVerb_word_count_task_1 > 0 ){
            lexical_resource1_1_comment += `<br> - ${goodVerb_word_array_task_1} là những động từ hay, nên sử dụng để diễn tả sự thay đổi về số liệu/ xu hướng của các đối tượng so sánh. Và bạn đã thể hiện trong bài viết, đây sẽ là 1 điểm cộng cho bạn!`
        }
        else{
        }

        if(well_adjective_and_adverb_word_count_task_1  == 1){
            lexical_resource1_1_comment += `<br> - ${well_adjective_and_adverb_word_array_task_1} là tính từ/ trạng từ cũng khá hay nếu bạn muốn nhấn mạnh xu hướng tăng/giảm/giữ nguyên và nó cũng khiến động từ trước nó tăng thêm 1 cấp độ. Ví dụ: increase drammatically (tăng mạnh),... Tuy nhiên bạn đang sử dụng hơi ít nó, hãy thêm tầm 2,3 trạng từ/ tính từ nữa để nhấn mạnh xu hướng nhé. Bạn có thể tham khảo 1 số trạng từ/ tính từ sau: <br>Tính từ: <br> - Chỉ tốc độ nhanh: dramatic, tremendous, significant, rapid, sharp, suddent, steep, substantial, remarkable, notable, swift,... <br> - Chỉ mức độ trung bình: noticeable, marked, moderate, marked, moderate, steady, gradual, consistent, constant,... <br>- Chỉ mức độ chậm: minimal, slight, slow, marginal <br><b>Trạng từ: </b> <br> - Chỉ mức độ nhanh: dramatically, tremendously, significantly, rapidly, sharply, suddenly, steeply, substantially, remarkably, notably, swiftly, quickly <br> - Chỉ mức độ trung bình: noticeably, markedly, moderately, steadily, gradually, consistently, constantly<br> - Chỉ mức độ chậm: minimally, slightly, slowly, marginally`
            if(well_adjective_and_adverb_word_count_task_1 >= 2 && well_adjective_and_adverb_word_count_task_1 <= 6){
                lexical_resource1_1_comment += `<br> -Trong bài viết, chúng tôi nhận thấy có sử dụng ${well_adjective_and_adverb_word_array_task_1} (là tính từ/ trạng từ cũng khá hay nếu bạn muốn nhấn mạnh xu hướng tăng/giảm/giữ nguyên) và có vẻ cũng được sử dụng nhuần nhuẫn, linh hoạt rồi đó. Good job !. Bạn có thể tham khảo thêm 1 số tính từ/ trạng từ kiểu này nữa như <br>Tính từ: <br> - Chỉ tốc độ nhanh: dramatic, tremendous, significant, rapid, sharp, suddent, steep, substantial, remarkable, notable, swift,... <br> - Chỉ mức độ trung bình: noticeable, marked, moderate, marked, moderate, steady, gradual, consistent, constant,... <br>- Chỉ mức độ chậm: minimal, slight, slow, marginal <br>Trạng từ: <br> - Chỉ mức độ nhanh: dramatically, tremendously, significantly, rapidly, sharply, suddenly, steeply, substantially, remarkably, notably, swiftly, quickly <br> - Chỉ mức độ trung bình: noticeably, markedly, moderately, steadily, gradually, consistently, constantly<br> - Chỉ mức độ chậm: minimally, slightly, slowly, marginally`
            }
        }
        else{
            lexical_resource1_1_comment += `<br> - Nếu bạn muốn tăng band điểm writing, bạn có thể làm writing task 1 của bạn trở nên hay hơn bằng các từ vựng miêu tả tốc độ thay đổi. Ví dụ: increase slightly (tăng nhẹ), decrease significantly (giảm sâu),...Bạn có thể tham khảo thêm 1 số tính từ/ trạng từ kiểu này nữa như <br>Tính từ: <br> - Chỉ tốc độ nhanh: dramatic, tremendous, significant, rapid, sharp, suddent, steep, substantial, remarkable, notable, swift,... <br> - Chỉ mức độ trung bình: noticeable, marked, moderate, marked, moderate, steady, gradual, consistent, constant,... <br>- Chỉ mức độ chậm: minimal, slight, slow, marginal <br>Trạng từ: <br> - Chỉ mức độ nhanh: dramatically, tremendously, significantly, rapidly, sharply, suddenly, steeply, substantially, remarkably, notably, swiftly, quickly <br> - Chỉ mức độ trung bình: noticeably, markedly, moderately, steadily, gradually, consistently, constantly<br> - Chỉ mức độ chậm: minimally, slightly, slowly, marginally ` 
        }


        if(spelling_grammar_error_count_task_1 == 0){
            lexical_resource1_2_comment  = ` Hệ thống nhận thấy bạn không có bất kì lỗi chính tả nào. Bạn làm tốt lắm, hãy cố gắng luôn dành 3-5 phút cuối để kiểm tra lại lỗi sai ngữ pháp, chính tả để tránh đánh mất điểm số không đáng nhé !<br> Ngoài ra bạn cũng phải chú ý để thì của bài viết xem ở hiện tại/ quá khứ hay tương lai.`

        }
        else if(spelling_grammar_error_count_task_1 == 1){
            lexical_resource1_2_comment  = `Trong bài viết bạn đã có ${spelling_grammar_error_count_task_1} lỗi ngữ pháp, chính tả. Cụ thể ở đoạn ${spelling_grammar_error_essay_task_1} (đã được bôi đỏ trong phần bài làm). Hãy lưu ý điều này vì lỗi sai chính tả sẽ làm giảm số điểm bài làm của bạn`
        }
        else if(spelling_grammar_error_count_task_1 >= 2 && spelling_grammar_error_count_task_1 <= 3){
            lexical_resource1_2_comment  = ` Trong bài viết bạn đã có ${spelling_grammar_error_count_task_1} lỗi ngữ pháp, chính tả. Cụ thể ở đoạn ${spelling_grammar_error_essay_task_1} (đã được bôi đỏ trong phần bài làm). Hãy lưu ý điều này vì lỗi sai chính tả sẽ làm giảm số điểm bài làm của bạn`
        }
        else{
            lexical_resource1_2_comment  = ` Trong bài viết bạn đã có ${spelling_grammar_error_count_task_1} lỗi ngữ pháp, chính tả. Cụ thể ở đoạn ${spelling_grammar_error_essay_task_1} (đã được bôi đỏ trong phần bài làm). Hãy lưu ý điều này vì lỗi sai chính tả sẽ làm giảm số điểm bài làm của bạn`
        }
        
        if (simple_sentences_count_task_1 >= 2 && complex_sentences_count_task_1 >= 2 && compound_sentences_count_task_1 >= 2){
            grammatical_range_and_accuracy1_1_comment = `Bạn đã sử dụng tương đối ổn và linh hoạt các cấu trúc ngữ pháp. Cụ thể, có ${simple_sentences_count_task_1} câu đơn (${simple_sentences_task_1}), có ${compound_sentences_count_task_1} câu ghép ở các câu ${position_compound_sentences} và ${complex_sentences_count} câu phức (${complex_sentences})` 
        }
        
        else{
            if(simple_sentences_count_task_1 < 2 && sentence_count > 4){
                grammatical_range_and_accuracy1_1_comment += `Bạn đang sử dụng hơi ít câu đơn. Cụ thể, số câu đơn có trong bài là ${simple_sentences_count_task_1} ${simple_sentences_task_1}. Hãy lưu ý thêm những câu đơn (Cấu tạo gồm 1 mệnh đề : S + V) ` 
            }
            else if(compound_sentences_count_task_1 < 2 && sentence_count_task_1 > 4){
                grammatical_range_and_accuracy1_1_comment += `Về câu ghép, số câu ghép có trong bài là ${compound_sentences_count_task_1} "${compound_sentences_task_1}". Bạn nên thêm ít nhất 2 câu ghép (Cấu trúc: Mệnh đề 1; trạng từ liên kết, mệnh đề 2) vào mỗi bài writing task 1. 1 tip cho bạn là dùng các liên từ (FANBOYS) hoặc dấu ;` 
            }
            else if(complex_sentences_count_task_1 < 2 && sentence_count_task_1 > 4){
                grammatical_range_and_accuracy1_1_comment += `Số lượng câu phức có trong bài là ${complex_sentences_count_task_1} ${complex_sentences_task_1}. Để tăng tính liên kết cho tiêu chí Cohesion and Coherence (CC) và sự linh hoạt trong cách sử dụng ngữ pháp, bạn nên sử dụng nhiều câu phức (nên ít nhất là 2)(Câu phức là loại câu được tạo thành từ hai hay nhiều mệnh đề, trong đó phải có một mệnh đề độc lập (mệnh đề chính) và ít nhất một mệnh đề phụ thuộc (mệnh đề phụ)). <br> - Sử dụng liên từ chỉ nguyên nhân, kết quả như: as, since, before hoặc cấu trúc Because of/Due to/Owing to,... <br> - Sử dụng liên từ chỉ quan hệ nhượng bộ: Although/Though/Even though, Despite/In spite of,... <br> - Liên từ chỉ quan hệ tương phản: While/Whereas  (trong khi) <br> Sử dụng Liên từ chỉ mục đích: In order that/so that (để mà) , vv Đây là 1 số các liên từ dùng để liên kết tạo thành câu phức` 
            }
            else if (sentence_count_task_1 <= 4){
                grammatical_range_and_accuracy1_1_comment += `Số lượng câu có trong bài là quá ít. Cụ thể ${sentence_count_task_1} câu. Điều này sẽ ảnh hưởng đến điểm và tất cả tiêu chí đi kèm. Nếu như bạn đang sử dụng quá nhiều câu phức hoặc câu ghép dẫn đến không đủ số câu, hãy cố gắng sử dụng thêm câu đơn để khắc phục. Chúng tôi khuyên bạn nên viết trong khoảng 8 - 12 câu cho task 1 là vừa đẹp !` 
            }
        }
        grammatical_range_and_accuracy1_1_comment += `<br> Ở phần Sentence Structure của tiêu chí Grammaticall Range and Accuracy, bạn nên bao gồm nhiều cấu trúc câu khác nhau như simple sentence (câu đơn), complex sentence (câu phức) và compound sentence (câu ghép). Và nên sử dụng nhiều hơn hoặc bằng 2 câu đối với mỗi loại, điều này sẽ khiến giám khảo đánh giá bài viết của bạn cao hơn trong kho tàng ngữ pháp của bạn, cũng như làm tăng tính liên kết của bài viết qua các từ nối của complex, compound sentences. Trong bài viết của bạn có ${simple_sentences_count_task_1} câu đơn, ${compound_sentences_count_task_1} câu ghép và ${complex_sentences_count_task_1} câu phức <br> Ví dụ về câu đơn: Copyright laws are necessary for society. (chỉ có 1 mệnh đề) <br> Ví dụ về câu ghép:  Copyright laws are necessary for society, as they provide rewards and protection to original artwork creators.(nhiều hơn 1 mệnh đề) <br> Ví dụ về câu ghép: Because they provide rewards and protection, copyright laws are necessary for society.(có một mệnh đề chính và một hoặc nhiều hơn một mệnh đề phụ thuộc) ` 

        if(spelling_grammar_error_count_task_1 == 0){
            grammatical_range_and_accuracy1_2_comment  = ` Hệ thống nhận thấy bạn không có bất kì lỗi ngữ pháp nào. Bạn làm tốt lắm, hãy cố gắng luôn dành 3-5 phút cuối để kiểm tra lại lỗi sai ngữ pháp, chính tả để tránh đánh mất điểm số không đáng nhé !<br> Bạn cũng nên lưu ý một số lỗi ngữ pháp cơ bản dễ sai như vị trí dấu câu, thì của bài viết.`

        }
        else if(spelling_grammar_error_count_task_1 == 1){
            grammatical_range_and_accuracy1_2_comment  = `Số lỗi ngữ pháp: ${spelling_grammar_error_count_task_1}, chính tả. Cụ thể ở đoạn ${spelling_grammar_error_essay_task_1} (đã được bôi đỏ trong phần bài làm). Hãy lưu ý điều này vì lỗi sai chính tả sẽ làm giảm số điểm bài làm của bạn`
        }
        else if(spelling_grammar_error_count_task_1 >= 2 && spelling_grammar_error_count_task_1 <= 3){
            grammatical_range_and_accuracy1_2_comment  = ` Số lỗi ngữ pháp: ${spelling_grammar_error_count_task_1} lỗi ngữ pháp, chính tả. Cụ thể ở đoạn ${spelling_grammar_error_essay_task_1} (đã được bôi đỏ trong phần bài làm). Hãy lưu ý điều này vì lỗi sai chính tả sẽ làm giảm số điểm bài làm của bạn`
        }
        else{
            grammatical_range_and_accuracy1_2_comment  = ` Số lỗi ngữ pháp: ${spelling_grammar_error_count_task_1} lỗi ngữ pháp, chính tả. Cụ thể ở đoạn ${spelling_grammar_error_essay_task_1} (đã được bôi đỏ trong phần bài làm). Hãy lưu ý điều này vì lỗi sai chính tả sẽ làm giảm số điểm bài làm của bạn`
        }


    }
    else{
        task_achievement1_1_comment = task_achievement1_2_comment = task_achievement1_3_comment = coherenceandcohesion1_1_comment = coherenceandcohesion1_2_comment = coherenceandcohesion1_3_comment = lexical_resource1_1_comment = lexical_resource1_2_comment = grammatical_range_and_accuracy1_1_comment = grammatical_range_and_accuracy1_2_comment = `Độ dài bài viết quá ngắn hoặc chưa viết. Hệ thống không chấm được bài này, hãy thử lại bằng bài viết khác. Đọc kĩ yêu cầu đề bài (Ít nhất 150 từ cho task 1 và 250 từ cho task 2)`
    }

    if (CurrentEssayTask1Final == 2 && WordCountTask1Final > 40){

        if (length_essay_task_2 < 200){
            task_achievement2_1_comment = `Độ dài bài viết của bạn quá ngắn. Đối với Task 2 độ dài tối thiểu là 250 từ. Bạn nên viết trong khoảng 270 - 290 từ. Việc viết thiếu sẽ ảnh hưởng rất nhiều đến kết quả của bạn và thông thường sẽ nhận được < 5.0 band cho task 2 `
        
        }
        else{
            if (length_essay_task_2 >= 200 && length_essay_task_2 < 250 ){
                task_achievement2_1_comment = `Có vẻ như độ dài của bài viết bạn chưa đạt yêu cầu. Bạn nên cố gắng để độ dài Task 2 kéo dài lên > 250 từ để cải thiện điểm cho phần Task Achievement. Nếu bạn chưa đủ độ dài do vấn đề thời gian, hãy cải thiện bằng cách luyện tập nhiều, tập trung cao độ cho bài viết của mình. Đối với task 2 bạn nên dùng 40 phút để hoàn thiện (5 - 7 phút lên dàn ý, 25 - 30 phút viết bài và 5 phút dành cho việc soát lỗi). `
            }
            
            else if(length_essay_task_2 >= 250 && length_essay_task_2 < 260 ){
                task_achievement2_1_comment = `Độ dài cho phần writing task 2 của bạn đã đạt yêu cầu. Tuy vậy, bạn nên mở rộng hơn 1 chút nữa và cố gắng viết trong khoảng 260 - 280 từ. Bạn vẫn được điểm cho phần này nhé !`;
            }
            else if(length_essay_task_2 >= 260 && length_essay_task_2 < 300){
                task_achievement2_1_comment = `Tuyệt vời ! Độ dài bài viết của bạn cho task 2 là hoàn hảo rồi. Bạn sẽ đạt tối đa điểm cho phần độ dài trong bài thi lần này lẫn bài thi thật (real exam) khi bạn thi Ielts. Hãy tiếp tục phát huy nhé ^^. `
            }
            else if(length_essay_task_2 >= 300 && length_essay_task_2 < 320){
                task_achievement2_1_comment = `Độ dài bài viết của bạn là quá dài. Việc này sẽ ảnh hưởng tới thời gian làm bài của bạn là rất lớn. Hãy giới hạn bài viết của bạn lại trong khoảng 260 - 280 từ để đạt điểm tối đa của phần length(độ dài)`
            }
            else if (length_essay_task_2 >= 320){
                task_achievement2_1_comment = `No comment -.- Sao bạn viết dài thế !? Bạn nên viết trong khoảng 260 - 280 từ thôi để đạt yêu cầu đề bài và cũng vừa thời gian làm bài. Bạn sẽ KHÔNG đạt điểm cho phần độ dài !`
            
            }

            if(paragraph_essay_task_2 < 3){
                task_achievement2_1_comment += `Ngoài ra, bạn nên chia bài làm của bạn thành 3 - 4 phần (đoạn). Đối với writing task 2, Phần 1: Introduction, phần 2: Supporting Paragraph 1: Thân bài 1, phần 3: Supporting Paragraph 2: Thân bài 2, phần 4: Conclusion: Kết luận <br> Phần thân bài thường bao gồm:<br> - Topic sentence: Câu chủ đề<br> - Explanation: Giải thích <br> - Example: Ví dụ cụ thể `
            }
            else if(paragraph_essay_task_2 == 3 || paragraph_essay_task_2 == 4){
                task_achievement2_1_comment += `Ngoài ra, bạn chia bố cục bài làm hợp lý rồi (3 - 4 đoạn) để phù hợp logic, cấu trúc bài làm và phù hợp với các tiêu chí khác như Coherence and Cohesion`
            }
            else{
                task_achievement2_1_comment += `Bạn đang chia bố cục không hợp lý cho lắm. Thông thường ở task 2, bạn nên chia làm 4 phần tương ứng Introduction, Supporting Paragraph 1,  Supporting Paragraph 2, Conclusion. Việc chia thành nhiều đoạn sẽ gây ra nhiễu thông tin và ảnh hưởng tính mạch lạc và gắn kết của bài làm `
            }
        }

        
    }
    else{
        task_achievement2_1_comment = task_achievement2_2_comment = task_achievement2_3_comment = coherenceandcohesion2_1_comment = coherenceandcohesion2_2_comment = coherenceandcohesion2_3_comment = lexical_resource2_1_comment = lexical_resource2_2_comment = grammatical_range_and_accuracy2_1_comment = grammatical_range_and_accuracy2_2_comment = `Độ dài bài viết quá ngắn hoặc chưa viết. Hệ thống không chấm được bài này, hãy thử lại bằng bài viết khác. Đọc kĩ yêu cầu đề bài (Ít nhất 150 từ cho task 1 và 250 từ cho task 2)`
    }
    submitTest();
}
