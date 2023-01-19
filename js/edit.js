/**
 * determines which type of input is to be added
 * gets corresponding input-group div
 * appends input-group to the corresponding parent div
 * @param type
 */
function addInput(type) {
    let id;
    let inputName;
    let labelText;
    if (type === 'pre') {
        id = "ksDiv";
        inputName = "kskills[]";
        labelText = "Known Skill:";
    } else if (type === 'new') {
        id = "nsDiv";
        inputName = "nskills[]";
        labelText = "New Skill:";
    }

    let div = getInputGroupDiv(type,labelText,inputName);

    $("#"+id).append(div);
}

/**
 * creates and fills Input-Group div
 * @param type
 * @param labelText
 * @param inputName
 * @returns {HTMLDivElement}
 */
function getInputGroupDiv(type, labelText, inputName) {
    let div = $("<div></div>");
    let label = $("<label></label>");
    let input = $("<input>");
    let addButton = $("<button></button>");
    let delButton = $("<button></button>");

    div.attr("class","input-group mb-2");
    label.attr("class","input-group-text");
    label.html(labelText);
    input.attr("class","form-control");
    input.attr("name",inputName);
    addButton.attr("type","button");
    addButton.attr("class","btn btn-outline-primary");
    addButton.attr("onclick","addInput('"+ type +"')");
    addButton.html("+");
    delButton.attr("type","button");
    delButton.attr("class","btn btn-outline-danger");
    delButton.attr("onclick","this.parentElement.remove()");
    delButton.html("X");

    div.append(label);
    div.append(input);
    div.append(addButton);
    div.append(delButton);

    return div;
}

/**
 * deletes skill and removes/empties corresponding input
 * @param type
 * @param skill
 * @param button
 */
function deleteSkill(type,skill,button) {
    let id = $("#id").val();
    $.post("http://localhost/azubiFramework/index.php", "id=" + id + "&type=" + type + "&skill=" + skill + "&controller=AjaxDeleteSkill");
    removeParent(button);
}

/**
 * removes parent-element of given element if parent-element is not the only element of its parent
 * otherwise sets value of parent-elements second child to ""
 * @param element
 */
function removeParent(element) {
    if ($(element).parent().parent().children().length > 1) {
        $(element).parent().remove();
    } else {
        $(element).parent().find("input").val("");
    }
}