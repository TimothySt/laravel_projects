window.onload = function () {
    handleAuthorChange();
    handlePublisherChange();
};


function addAuthor() {
    // pobieramy selecta
    let select = document.getElementById('authors');
    // pobieramy wybraną opcję
    let selectedOption = select.options[select.selectedIndex];
    // tworzymy nowy element checkbox
    let newCheckboxString = '<input type="checkbox" name="authors[]" onchange="removeAuthor(this)"  value="' +
        selectedOption.value + '" checked> ' + selectedOption.text + '</input>';
    // pobieramy hidden input z opisem autora
    let authorDescription = document.getElementById('authorDescription' + selectedOption.value).value;
    // tworzymy nowy hidden input z opisem autora
    let newHiddenInputString = '<input type="hidden" ' + 'id="authorDescription' + selectedOption.value + '"' +
        ' value="' +
        authorDescription + '" name="authorsDescription[]">';
    //usuwanie starego opisu autora
    let oldAuthorDescription = document.getElementById('authorDescription' + selectedOption.value);
    oldAuthorDescription.remove();
    // dodajemy nowy checkbox i hidden input do selected_authors
    let selected_authors = document.getElementById('selected_authors');
    selected_authors.innerHTML += newCheckboxString + newHiddenInputString;
    //usuwamy opcje z selecta
    select.remove(select.selectedIndex);

    // jeśli nie ma już opcji w select to ukrywamy przycisk dodaj autora
    if (select.length == 0) {
        let unhidden_autor = document.getElementById('unhidden_autor');
        unhidden_autor.style.display = "none";
    }
    handleAuthorChange();
}

function removeAuthor(checkbox) {
    // pobieramy selecta
    let select = document.getElementById('authors');
    // tworzymy nowy element option
    let newOption = document.createElement('option');
    // ustawiamy wartość i tekst nowego optiona
    newOption.value = checkbox.value;
    newOption.text = checkbox.nextSibling.textContent;
    // pobieramy hidden input z opisem autora
    let authorDescription = document.getElementById('authorDescription' + checkbox.value).value;
    // tworzymy nowy hidden input z opisem autora
    let newHiddenInputString = '<input type="hidden" ' + 'id="authorDescription' + checkbox.value + '"' +
        ' value="' +
        authorDescription + '">';
    //usuwanie starego opisu autora
    let oldAuthorDescription = document.getElementById('authorDescription' + checkbox.value);
    oldAuthorDescription.remove();
    // dodajemy nowy hidden input do authorDescriptions
    let authorDescriptions = document.getElementById('authorDescriptions');
    authorDescriptions.innerHTML += newHiddenInputString;
    // dodajemy nowy option do selecta
    select.add(newOption);
    //usuwamy text po checkboxie
    checkbox.nextSibling.remove();
    // usuwamy checkboxa
    checkbox.remove();

    handleAuthorChange();
}

function handleAuthorChange() {
    // pobieramy selecta
    let select = document.getElementById('authors');
    // pobieramy wybraną opcję
    let selectedOption = select.options[select.selectedIndex];
    if (selectedOption.value == "new") {
        // pobieramy elementy o klassie hidden_author wszystkie mają atrybut display none
        let hidden_author = document.querySelectorAll('.hidden_author');
        // ustawiamy klase hidden_author na display block
        hidden_author.forEach(element => {
            element.style.display = "block";
        });
        let unhidden_autor = document.getElementById('unhidden_autor');
        unhidden_autor.style.display = "none";
        // pobieramy element z opisem autora div
        let authorDescriptionsDiv = document.getElementById('authorDescriptionsDiv');
        // zrob hidden 
        authorDescriptionsDiv.style.display = "none";

    } else {
        // pobieramy elementy o klassie hidden_author wszystkie mają atrybut display none
        let hidden_author = document.querySelectorAll('.hidden_author');
        // ustawiamy klase hidden_author na display none
        hidden_author.forEach(element => {
            element.style.display = "none";
        });
        let unhidden_autor = document.getElementById('unhidden_autor');
        unhidden_autor.style.display = "inline-block";
        // pobieramy element z opisem autora div
        let authorDescriptionsDiv = document.getElementById('authorDescriptionsDiv');
        // zrob block
        authorDescriptionsDiv.style.display = "block";
        // pobieramy hidden input z opisem autora
        let authorDescription = document.getElementById('authorDescription' + selectedOption.value).value;
        if (authorDescription == "") {
            authorDescriptionNot = "Ten autor nie ma jeszcze opisu."
        }
        // tworzymy nowy element p
        let newP = document.createElement('p');
        // ustawiamy tekst nowego p
        newP.textContent = authorDescription;
        // uzyj authorDescriptionsDiv
        authorDescriptionsDiv.innerHTML = "";
        // dodajemy h3 Opis autora do authorDescriptionsDiv
        let newH3 = document.createElement('h3');
        newH3.textContent = "Opis autora";
        authorDescriptionsDiv.appendChild(newH3);
        // dodajemy nowy p do authorDescriptionsDiv
        authorDescriptionsDiv.appendChild(newP);

    }
}

function addNewAuthor() {
    // pobieramy wartość z inputa new_author
    let newAuthorName = document.getElementById('new_author').value;
    // pobieramy wartość z textarea new_author_description
    let newAuthorDescription = document.getElementById('new_author_description').value;
    // sprawdzamy czy pola nie są puste
    if (newAuthorName.trim() === '') {
        alert('Pola Imię i nazwisko jest wymagane!');
        return;
    }
    // sprawdzamy czy nazwa autora jest unikalna w wybranych autorach
    let selected_authors = document.getElementById('selected_authors');
    let checkboxes = selected_authors.getElementsByTagName('input');
    for (let i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].value === newAuthorName) {
            alert('Autor o tej nazwie już istnieje!');
            return;
        }
    }
    // sprawdzamy czy nazwa autora jest unikalna w opcjach selecta
    let select = document.getElementById('authors');
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].text === newAuthorName) {
            alert('Autor o tej nazwie już istnieje!');
            return;
        }
    }

    // tworzymy nowy element checkbox
    let newCheckboxString = '<input type="checkbox" name="authors[]" onchange="removeAuthor(this)"  value="' +
        newAuthorName + '" checked> ' + newAuthorName + '</input>';
    // tworzymy nowy hidden input z opisem autora
    let newHiddenInputString = '<input type="hidden" ' + 'id="authorDescription' + newAuthorName + '"' +
        ' value="' +
        newAuthorDescription + '" name="authorsDescription[]">';
    // dodajemy nowy checkbox i hidden input do selected_authors
    selected_authors.innerHTML += newCheckboxString + newHiddenInputString;
    // czyscimy pola formularza
    document.getElementById('new_author').value = '';
    document.getElementById('new_author_description').value = '';
    // ustawiamy select na pierwszą opcję
    document.getElementById('authors').selectedIndex = 0;
    handleAuthorChange();
}

// publisher
function addNewPublisher() {
    // pobieramy wartość z inputa new_publisher
    let newPublisherName = document.getElementById('new_publisher').value;
    // pobieramy wartość z textarea publisher_description
    let newPublisherDescription = document.getElementById('publisher_description').value;
    let select = document.getElementById('publisher_id');
    // sprawdzamy czy pola nie są puste
    if (newPublisherName.trim() === '') {
        select.selectedIndex = 0;
        return;
    }
    // sprawdzamy czy nazwa wydawnictwa jest unikalna w select
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].text === newPublisherName) {
            // ustawiamy select na tą opcję
            select.selectedIndex = i;

            return;
        }
    }
    // tworzymy nowy element option
    let newOption = document.createElement('option');
    // ustawiamy wartość i tekst nowego optiona
    newOption.value = newPublisherName;
    newOption.text = newPublisherName;
    // tworzymy nowy hidden input z opisem wydawnictwa
    let newHiddenInputString = '<input type="hidden" ' + 'id="publisherDescription' + newPublisherName + '"' +
        ' value="' +
        newPublisherDescription + '">';
    // dodajemy nowy option do selecta
    select.add(newOption);
    // dodajemy nowy hidden input do publisherDescriptions
    // dodajemy nowy hidden input do publisherDescriptionDiv
    let publisherDescriptionDiv = document.getElementById('publisherDescriptionDiv');
    publisherDescriptionDiv.innerHTML += newHiddenInputString;
    // czyscimy pola formularza
    document.getElementById('new_publisher').value = '';
    document.getElementById('publisher_description').value = '';
    // ustawiamy select na nową opcję
    select.selectedIndex = select.options.length - 1;
    handlePublisherChange();
}

// find publisher
function findPublisher() {
    let newPublisherName = document.getElementById('new_publisher').value;
    let newPublisherDescription = document.getElementById('publisher_description').value;
    let select = document.getElementById('publisher_id');
    if (newPublisherName.trim() === '') {
        return;
    }
    // sprawdzamy czy nazwa wydawnictwa jest unikalna w select
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].text === newPublisherName) {
            // ustawiamy select na tą opcję
            select.selectedIndex = i;
            let publisher_description_input = document.getElementById('publisher_description');
            // ustawiamy opis wydawnictwa na edytowalny
            publisher_description_input.readOnly = true;
            handlePublisherChange();
            return;
        }
    }
    // ustawiamy select na pierwszą opcję
    select.selectedIndex = 0;
    // ustaw nazwę wydawnictwa w input
    let publisher_input = document.getElementById('new_publisher');
    publisher_input.value = newPublisherName;
    console.log(newPublisherName);
    // pobieramy elementy o klassie hidden_publisher wszystkie mają atrybut display none
    let hidden_publisher = document.querySelectorAll('.hidden_publisher');
    // ustawiamy klase hidden_publisher na display block
    hidden_publisher.forEach(element => {
        element.style.display = "block";
    });
    // tworzymy input z opisem wydawnictwa
    let publisher_description_input = document.getElementById('publisher_description');
    // ustawiamy opis wydawnictwa na edytowalny
    publisher_description_input.readOnly = false;
    publisher_description_input.value = "";

}

function handlePublisherChange() {
    // pobieramy selecta
    let select = document.getElementById('publisher_id');
    // pobieramy wybraną opcję
    let selectedOption = select.options[select.selectedIndex];
    if (selectedOption.index == 0) {
        // pobieramy elementy o klassie hidden_publisher wszystkie mają atrybut display block
        let hidden_publisher = document.querySelectorAll('.hidden_publisher');
        // ustawiamy klase hidden_publisher na display none
        hidden_publisher.forEach(element => {
            element.style.display = "none";
        });
        // pobieramy element z nazwą wydawnictwa
        let publisherName = document.getElementById('new_publisher').value;
        // pobieramy element z opisem wydawnictwa
        let publisherDescription = document.getElementById('publisher_description').value;
        //czyścimy pola
        document.getElementById('new_publisher').value = '';
        document.getElementById('publisher_description').value = '';

    } else {
        // pobieramy elementy o klassie hidden_publisher wszystkie mają atrybut display block
        let hidden_publisher = document.querySelectorAll('.hidden_publisher');
        // ustawiamy klase hidden_publisher na display none
        hidden_publisher.forEach(element => {
            element.style.display = "block";
        });
        // pobieramy element z nazwą wydawnictwa
        let publisherName = selectedOption.value;
        // ustawiamy nazwę wydawnictwa w input
        let publisher_input = document.getElementById('new_publisher');
        publisher_input.value = publisherName;
        // pobieramy hidden input z opisem wydawnictwa
        let publisherDescription = document.getElementById('publisherDescription' + selectedOption.value).value;
        let publisherDescriptionPlaceHolder = "";
        // tworzymy nowy element name=publisher_description nie p
        let newP = document.getElementById('publisher_description');
        // ustawiamy tekst nowego p
        newP.textContent = publisherDescription;
        let publisher_description_input = document.getElementById('publisher_description');
        publisher_description_input.value = publisherDescription;
    }
    function CheckData() {
        //sprawdzanie czy wybrano wydawnictwo
        let publisher = document.getElementById("publisher_id").value;
        let bool = true;
        if (publisher == "new") {
            let new_publisher = document.getElementById("new_publisher").value;
            if (new_publisher == "") {
                alert("Wybierz lub dodaj wydawnictwo");
                bool = false;
            } else {
                let new_publisher_description = document.getElementById("publisher_description").value;
                addNewPublisher();
            }
        }
        //sprawdzanie czy wybrano autora
        let author = document.getElementById("authors").value;
        let bool1 = true;
        if (author == "new") {
            let new_author = document.getElementById("new_author").value;
            bool1 = false;
            if (new_author == "") {
                bool1 = false;
                //pobierz checkboxy
                let checkboxes = document.getElementsByName("authors[]");
                //sprawdz czy ktorys jest zaznaczony
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        bool1 = true;
                        console.log("zaznaczony");
                    }
                }
                if (!bool1) {
                    alert("Wybierz lub dodaj autora");
                }
            } else {
                let new_author_description = document.getElementById("new_author_description").value;
                addNewAuthor();
            }
        } else {
            addAuthor();
        }
        let bool2 =bool&&bool1;
        return bool2;
    }
}