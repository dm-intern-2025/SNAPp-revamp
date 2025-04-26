<x-layouts.app>

<!DOCTYPE html>
<html>

<head>
    <title>Contracts</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="./styles/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script src="https://unpkg.com/@kissflow/lowcode-client-sdk@latest/dist/kfsdk.umd.js"></script>
    <script src="./scripts/functions.js"></script>
</head>

<body>
    <table id="bills-table">

        <thead>
            <tr>
                <th>Contract Filename</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div id="dialog"></div>
</body>

</html>
<script>
$(document).ready(async function () {

    kf = await window.kf.initialize();
    const { Name, Email, _id } = kf.user;
    console.log(kf.user)

    const url = `${process.URL}/customers_snap?email=${Email}`
    let resp = await kf.api(url)
    console.log(resp)

    // function to fetch data from API endpoint
    $.ajax({
        url: `${process.URL}/contracts_gcs/${resp.Customer_Short_Name}`,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);

            // replace table content with response data
            var rows = "";
            for (var i = 0; i < data.length; i++) {
                var fn = data[i].replace("snapp_contracts/", "")
                rows += "<tr>";
                rows += "<td data-label='Contract Name'>" + (fn || '') + "</td>";
                rows += "<td data-label='Action'><button onclick='generateSignedURL(\"" + (data[i] || '') + "\")'>View</button></td>";
                rows += "</tr>";
            }
            $("#bills-table tbody").html(rows);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle the error
            console.log(errorThrown);
        }
    });

});
function generateSignedURL(contractName) {
    console.log(contractName)

    const contract = encodeURIComponent(contractName)

    $.ajax({
        url: `${process.URL}/sign_url_contract/` + contract,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // create an embed element for the PDF file
            var $embed = $("<embed>").attr("src", data.url).attr("type", "application/pdf").css("width", "100%").css("height", "500px");

            // display the PDF preview in a dialog
            $("#dialog").html($embed).dialog({
                title: "View Contract",
                width: 850,
                height: 600
            });
        },

        error: function (jqXHR, textStatus, errorThrown) {
            // Handle the error
            console.log(errorThrown);
            $("#dialog").html("<p>Sorry, there was an error generating the signed URL.</p>").dialog({
                title: "Error",
                width: 400,
                height: 200
            });
        }
    });
}
</script>

</x-layouts.app>
