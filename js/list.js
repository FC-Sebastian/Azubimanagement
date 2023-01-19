const trashcans = $(".trash-can");
const searchbar = $("#search");
const searchbarList = $("#searchbarList");
const searchbarForm = $("[name='searchbarForm']");
const tableForm = $("#tableForm");
const hiddenInputs = $("#hiddenInputs");

//adding eventlisteners to the delete icons
trashcans.each(function () {
    this.click(function () {
        deleteAzubi(this.id);
    });
});

//adding eventListener to the searchbar
searchbar.on("input",function () {
    searchAutoComplete();
});

/**
 * main searchbar function
 * outputs matching searches or clears search recommendation list depending on search length
 */
function searchAutoComplete() {
    if (searchbar.val().length > 1) {
        let params = {"search" : searchbar.val(), "controller" : "SearchbarController"};
        $.post("http://localhost/azubiFramework/index.php", params, function (result) {
            searchbarList.html(getListItemsString(result));
        });
    } else {
        searchbarList.html("");
    }
}

/**
 * builds html string containing "<li>" entries from xmlHttpRequest-response array
 * @param response
 * @returns {string}
 */
function getListItemsString(response) {
    let searchResults = JSON.parse(response);
    let listItem = "";
    let listItems = "";
    for (let i = 0; i < searchResults.length; i++) {
        listItem = "<li onclick='executeSearch(this.textContent)' class='list-group-item searchListItem'>" + searchResults[i] + "</li>";
        listItems += listItem;
    }
    return listItems;
}

/**
 * sets the value of the search bar to 'search' and submits the form
 * @param search
 */
function executeSearch(search) {
    searchbar.val(search);
    searchbarForm.submit();
}

/**
 * deletes a single Azubi by id
 * and removes corresponding table element
 * then loads next Azubi
 * @param id
 * @param element
 * @param page
 */
function deleteAzubi(id,element,page) {

    let params = {"id" : id, "&controller" : "AjaxDelete"}
    $.post("http://localhost/azubiFramework/index.php", params,function (){
        let offset = tableForm.children().length - 2;
        if (page > 1) {
            offset = (page * offset) + (page - 1);
        }

        removeTableDivByLink(element);
        loadAzubi(1, offset);
    });
}

/**
 * removes table element by child element
 * @param element
 */
function removeTableDivByLink(element) {
    $(element).parent().parent().remove();
}

/**
 * loads the first table entries
 * @param limit
 * @param offset
 */
function onSiteLoad(limit,offset){
    loadAzubi(limit,offset);
}

/**
 * loads next azubis from offset till limit
 * @param limit
 * @param offset
 */
function loadAzubi(limit, offset) {
    let page = hiddenInputs.find(":first-child").val();
    let search = hiddenInputs.find(":nth-child(2)").val();
    let order = hiddenInputs.find(":nth-child(3)").val();
    let orderdir = hiddenInputs.find(":last-child").val();
    let params = {"controller":"AjaxTableGenerator", "tableLength":limit, "offset":offset, "search":search, "order":order, "orderdir":orderdir, "page":page}

    $.post("http://localhost/azubiFramework/index.php",
        params,
        function (result){
            let responseArray = JSON.parse(result);
            for (let i = 0; i < responseArray.length; i++) {
                let div = $("<div></div>").attr("class","row text-md-center text-center justify-content-center gx-1");
                div.html(responseArray[i]);
                tableForm.children().last().before(div);
            }
    });
}