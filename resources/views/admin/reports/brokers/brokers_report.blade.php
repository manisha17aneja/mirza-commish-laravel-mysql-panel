<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Broker Report</title>
    <style>
        thead{display: table-header-group;}
        tfoot {display: table-row-group;}
        tr {page-break-inside: avoid;}
    </style>
</head>
<body style=" font-family: system-ui, system-ui, sans-serif;">

<table style="margin-top: 5px;margin-bottom:5px;width: 100%">
    <tbody>
    <tr>
        <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">Brokers List</span></td>
    </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 5px" >
    <thead class="thead_style">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Work Phone</th>
        <th>Home Phone</th>
        <th>Mobile</th>
        <th>Fax</th>
        <th>Email</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody class="body_class">
    @foreach($brokers as $k=>$broker)
        <tr>
            <td>{{ $broker->id }}</td>
            <td>{{ $broker->entity_name }}</td>
            <td>{{ $broker->work_phone }}</td>
            <td>{{ $broker->home_phone }}</td>
            <td>{{ $broker->mobile_phone }}</td>
            <td>{{ $broker->fax }}</td>
            <td>{{ $broker->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
