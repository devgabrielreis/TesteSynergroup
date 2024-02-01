let clientsTableBody = document.getElementsByClassName("clients-table-body")[0];
let newClientPopup = document.getElementsByClassName("new-client-popup")[0];
let newClientPopupCloseBtn = document.getElementsByClassName("new-client-popup-close-btn")[0];
let clientViewPopup = document.getElementsByClassName("client-view")[0];
let clientViewCloseBtn = document.getElementsByClassName("close-information-popup")[0];
let reloadBtn = document.getElementsByClassName("reload-btn")[0];
let addClientBtn = document.getElementsByClassName("add-client-btn")[0];
let clients = [];
let currencies = [];

addClientBtn.onclick = function ()
{
    newClientPopup.style.display = "block";

    let currencySelector = document.getElementsByClassName("new-client-currency-code")[0];

    currencySelector.innerHTML = '';

    for(let currency of currencies)
    {
        let entry = document.createElement("option");
        entry.innerText = currency.abbreviation;
        entry.value = currency.code;
        currencySelector.appendChild(entry);
    }
}

clientViewCloseBtn.onclick = function ()
{
    clientViewPopup.style.display = "none";
}

newClientPopupCloseBtn.onclick = function ()
{
    newClientPopup.style.display = "none";
}

function formatDate(dateStr)
{
    let date = dateStr.split("-");

    let day = date[2];
    let month = date[1];
    let year = date[0];

    return `${day}/${month}/${year}`;
}

function onEntryClick()
{
    clientViewPopup.style.display = "block";

    document.getElementsByClassName("client-name")[0].innerHTML = this.querySelector(".client-name-cell").innerHTML;
    document.getElementsByClassName("client-code")[0].innerHTML = this.querySelector(".code-cell").innerHTML;
    document.getElementsByClassName("client-currency")[0].innerHTML = this.querySelector(".currency-code-cell").innerHTML;
    document.getElementsByClassName("client-creation-date")[0].innerHTML = this.querySelector(".creation-date-cell").innerHTML;
    document.getElementsByClassName("client-last-sale-date")[0].innerHTML = this.querySelector(".last-sale-date-cell").innerHTML;
    document.getElementsByClassName("client-total-sales")[0].innerHTML = this.querySelector(".total-sales-cell").innerHTML;
}

function clearTable()
{
    clientsTableBody.innerHTML = '';
}

function loadClientsTable()
{
    clearTable();

    for(let client of clients)
    {
        let row = document.createElement("tr");
        row.classList.add("clients-table-entry");

        let codeCell = document.createElement("td");
        codeCell.textContent = client.code;
        codeCell.classList.add("code-cell");
        row.appendChild(codeCell);

        let clientNameCell = document.createElement("td");
        clientNameCell.textContent = client.clientName;
        clientNameCell.classList.add("client-name-cell");
        row.appendChild(clientNameCell);

        let currencyCodeCell = document.createElement("td");
        currencyCodeCell.textContent = currencies.find((currency) => currency.code === client.currencyCode).abbreviation;
        currencyCodeCell.classList.add("currency-code-cell");
        row.appendChild(currencyCodeCell);

        let creationDateCell = document.createElement("td");
        creationDateCell.textContent = formatDate(client.creationDate);
        creationDateCell.classList.add("creation-date-cell");
        row.appendChild(creationDateCell);

        let lastSaleDateCell = document.createElement("td");
        if(client.lastSaleDate === null)
        {
            lastSaleDateCell.textContent = "-";
        }
        else
        {
            lastSaleDateCell.textContent = formatDate(client.lastSaleDate);
        }
        lastSaleDateCell.classList.add("last-sale-date-cell");
        row.appendChild(lastSaleDateCell);

        let totalSalesCell = document.createElement("td");
        totalSalesCell.textContent = client.totalSales.toFixed(currencies.find((currency) => currency.code === client.currencyCode).decimalPlaces);
        totalSalesCell.classList.add("total-sales-cell");
        row.appendChild(totalSalesCell);

        row.onclick = onEntryClick;

        clientsTableBody.appendChild(row);
    }
}

async function getClients()
{
    const url = "/api/client/get.php";

    try
    {
        const response = await fetch(url);

        if(response.status !== 200)
        {
            alert("Algo deu errado placeholder");
            clients = [];
        }
        else
        {
            const jsonResponse = await response.json();
            clients = jsonResponse;
        }
    }
    catch(error)
    {
        console.error("Erro no fetch placeholder", error);
    }
}

async function getCurrencies()
{
    const url = "/api/currency/get.php";

    try
    {
        const response = await fetch(url);

        if(response.status !== 200)
        {
            alert("Algo deu errado placeholder");
            clients = [];
        }
        else
        {
            const jsonResponse = await response.json();
            currencies = jsonResponse;
        }
    }
    catch(error)
    {
        console.error("Erro no fetch", error);
    }
}

async function loadPage()
{
    await getClients();
    await getCurrencies();
    loadClientsTable();
}

reloadBtn.onclick = () => loadPage();

async function submitNewClient()
{
    let clientCode = document.getElementsByClassName("new-client-code")[0].value;
    let clientName = document.getElementsByClassName("new-client-name")[0].value;
    let clientCurrencyCode = document.getElementsByClassName("new-client-currency-code")[0].value;
    
    const url = "/api/client/add.php";

    const requestBody = {
        code: clientCode,
        clientName: clientName,
        currencyCode: clientCurrencyCode
    }

    try
    {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestBody)
        });

        if(response.status === 200)
        {
            loadPage();
            alert("Cliente adicionado com sucesso");
            newClientPopup.style.display = "none";
        }
        else
        {
            const jsonResponse = await response.json();
            let apiResponse = jsonResponse;
            alert(apiResponse.error);
        }
    }
    catch(error)
    {
        alert("Erro no fetch");
        console.log("Erro no fetch", error);
    }
}

loadPage();
