let clientsTable = document.getElementsByClassName("client-list")[0];
let clients = [];

function loadClientsTable()
{
    for(let client of clients)
    {
        let row = document.createElement("tr");
        row.classList.add("clients-table-entry")

        let codeCell = document.createElement("td");
        codeCell.textContent = client.code;
        row.appendChild(codeCell);

        let clientNameCell = document.createElement("td");
        clientNameCell.textContent = client.clientName;
        row.appendChild(clientNameCell);

        let currencyCodeCell = document.createElement("td");
        currencyCodeCell.textContent = client.currencyCode;
        row.appendChild(currencyCodeCell);

        let creationDateCell = document.createElement("td");
        creationDateCell.textContent = client.creationDate;
        row.appendChild(creationDateCell);

        let lastSaleDateCell = document.createElement("td");
        lastSaleDateCell.textContent = client.lastSaleDate;
        row.appendChild(lastSaleDateCell);

        let totalSalesCell = document.createElement("td");
        totalSalesCell.textContent = client.totalSales;
        row.appendChild(totalSalesCell);

        clientsTable.appendChild(row);
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

async function intializePage()
{
    await getClients();
    loadClientsTable();
}

intializePage();
