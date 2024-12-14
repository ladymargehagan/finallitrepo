Upon reviewing the code I noticed some things you did wrong. INstead of taking the word options and queries from the database 's appropriare tables (exercise _word_bank), you took the translations and options from the translations and words table which is wrong and does not work. I need you to effect the following changes to syntax.


1. we need words from the exercese_word_bank table to show as the possible answers or destructors along with the correct answer. Students will then pick the correct answer and the check_answer.php will go through the database and use the is_answer column of the exercise_word_bank table to verify this. No extra and redundant logic is required.  
2. The correct translation doesnt need to show up as an answer option. We need words from the exercise_word_bank to show at the bottom.
3. We need to write queries and logic using the exercise_word_bank column. (Like explained above)


Before you make any change. Consult with me and lets brainstorm to be sure it is the right one.
---
yes please effect these changes in the necessary code. Including learn.php and check_answer.php