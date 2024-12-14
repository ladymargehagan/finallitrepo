class LearnGame {
    constructor() {
        this.hearts = 3;
        this.currentWord = null;
        this.courseData = document.getElementById('courseData');
        this.languageId = this.courseData.dataset.languageId;
        this.category = this.courseData.dataset.category;
        
        this.initializeElements();
        this.setupEventListeners();
        this.loadNextWord();
    }

    initializeElements() {
        this.elements = {
            hearts: document.querySelector('.hearts'),
            answerBox: document.getElementById('answerBox'),
            wordBank: document.getElementById('wordBank'),
            checkButton: document.getElementById('checkBtn'),
            questionText: document.querySelector('.question'),
            originalText: document.querySelector('.french-text'),
            exerciseType: document.querySelector('.exercise-type'),
            progressBar: document.querySelector('.progress')
        };
    }
} 