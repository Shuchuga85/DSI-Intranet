async function fetchTranslations(language) {

    const response = await fetch('scripts/proxy.php?action=getTranslations');
    const data = await response.text();
    
    const translations = {};
    const rows = data.split('\n').map(row => row.split(','));
    const [header, ...content] = rows;
    const languageIndex = header.indexOf(language);
    
    content.forEach(row => {
        const translatedrow = row.map(column => column.replace(/#coma#/g, ','));
        translations[translatedrow[0]] = translatedrow[languageIndex];
    });
    
    return translations;
}

async function lateCacheUpdate(language){
    

    const response = await fetch('scripts/proxy.php?action=getTranslations');
    const data = await response.text();
    
    const translations = {};
    const rows = data.split('\n').map(row => row.split(','));
    const [header, ...content] = rows;
    const languageIndex = header.indexOf(language);
    
    content.forEach(row => {
        const translatedrow = row.map(column => column.replace(/#coma#/g, ','));
        translations[translatedrow[0]] = translatedrow[languageIndex];
    });

    // Cache translations in local storage
    localStorage.setItem(`translations_${language}`, JSON.stringify(translations));
    return translations;
}

async function translatePage(language, target = null) {
    if (language.length != 2) {
        return;
    }
    const translations = await fetchTranslations(language);
    var pivot = document;
    if (target != null) {
        pivot = document.querySelector('#'+target); 
    }
    elements = pivot.querySelectorAll('[data-tr]');
    elements.forEach(function(element) {
        const key = element.getAttribute('data-tr');
        element.innerText = translations[key] == "" ? "missing" : translations[key];
    });
     // Translate input values
     const inputElements = pivot.querySelectorAll('input[data-tr]');
     inputElements.forEach(function(inputElement) {
         const key = inputElement.getAttribute('data-tr');
         inputElement.value = translations[key] == "" ? "missing" : translations[key];
     });
 
     // Translate button text
     const buttonElements = pivot.querySelectorAll('button[data-tr]');
     buttonElements.forEach(function(buttonElement) {
         const key = buttonElement.getAttribute('data-tr');
         buttonElement.innerText = translations[key] == "" ? "missing" : translations[key];
     });

     // Translate placeholder text
     const placeholderElements = pivot.querySelectorAll('input[placeholder][data-tr-ph]');
     placeholderElements.forEach(function(placeholderElement) {
         const key = placeholderElement.getAttribute('data-tr-ph');
         placeholderElement.placeholder = translations[key] == "" ? "missing" : translations[key];
     });

    // Translate label text
    const labelElements = pivot.querySelectorAll('label[data-tr-lb]');
    labelElements.forEach(function(labelElement) {
        const keep = labelElement.querySelector('[data-kp]')?.outerHTML ?? "";
        const key = labelElement.getAttribute('data-tr-lb');
        labelElement.innerHTML = translations[key] == "" ? "missing" : translations[key]+keep;
    });

    // Translate title attribute (tooltip text)
    const titleElements = pivot.querySelectorAll('[title][data-tr-tt]');
    titleElements.forEach(function(titleElement) {
        const key = titleElement.getAttribute('data-tr-tt');
        titleElement.title = translations[key] == "" ? "missing" : translations[key];
    });

    const htmlElements = pivot.querySelectorAll('html[lang]');
    htmlElements.forEach(function(htmlElement) {
        htmlElement.lang = language;
    });

    const langElements = pivot.querySelectorAll('select[lang]');
    langElements.forEach(function(langElement) {
        langElement.value = language;
    });

    const customElements = pivot.querySelectorAll('[data-tr-html]');
    customElements.forEach(function(customElement) {
        const key = customElement.getAttribute('data-tr-html');
        customElement.innerHTML = translations[key] == "" ? "missing" : translations[key];
    });
}

function changeLanguage(lang ,target = null) {
    translatePage(lang, target);
}

function getUserPrimaryLanguage() {
    const userLanguage = navigator.language || navigator.userLanguage;

    return userLanguage.split('-')[0];
}
function getQueryParam(param) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    return urlParams.get(param);
}

function setSelectedOption(value) {
    if (document.getElementById('languageSelect')) {
    }
    document.getElementById('languageSelect').value = value;
}

function fetchPreferred(){
    return new Promise((resolve, reject) => {
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "scripts/proxy.php?action=fetchPreferred", true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText) {
                var text = this.responseText=='null'||this.responseText==""? false : this.responseText;
                console.log(text);
                resolve(text);
            }  
        }

    };
    xhttp.onerror = function () {
        //reject(null);
    };
    xhttp.send();
});
}

function loadLanguage(target = null) {
    const langParam = getQueryParam('lang');
    if (langParam) {
        changeLanguage(langParam,target) ;
        return;
    }
    fetchPreferred().then(langPrefs => {
        if (langPrefs) {
            changeLanguage(langPrefs, target);
            return;
        }
    });
    /*
    const langPrefs = fetchPreferred();
    if (langPrefs) {
       changeLanguage(langPrefs,target);
        return;
    }*/
    const langCached = getCachedLang();
    if (langCached) {
        changeLanguage(langCached,target);
        return;
    }
    const userDefaultLanguage = getUserPrimaryLanguage();
    changeLanguage(userDefaultLanguage ,target);
}




function getCachedLang() {
    const cachedLang = localStorage.getItem('userLanguage');
    if (cachedLang) {
        return cachedLang;
    }
    return null;
}
function storeCachedLang(language) {
    localStorage.setItem('userLanguage', language);
}

loadLanguage();
//fetchPreferred();
