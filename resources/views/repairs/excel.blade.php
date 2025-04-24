<html>




<style>
    th {
        color: green;
    }
</style>
<table>
    <thead>
        <tr>
            <th>id</th>
            <th>რეგიონი</th>
            <th>ობიექტი</th>
            <th>მისამართი</th>
            <th>შინაარსი</th>
            <th>დანადგარის ლოკაციის ზუსტი აღწერა</th>
            <th>ხარვეზის გამოსწორების მიზენით ჩატარებული სამუშაოების დეტალური აღწერა</th>
            <th>დეფექტური აქტ(ებ)ის რეკვიზიტები</th>
            <th>ფილიალში გამოცხადების დრო</th>
            <th>ინვენტარის ნომერი/აგრეგატის უნიკალური კოდი (არსებობის შემთხვევაში)</th>
            <th>შემსრულებელი</th>
            <th>თარიღი</th>
            <th>სისტემა 1</th>
            <th>სისტემა 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $model['id'] }}</td>
            <td>{{ $model['region'] ? $model['region']['name'] : '' }}</td>
            <td>{{ $model['subject_name'] }}</td>
            <td>{{ $model['subject_address'] }}</td>
            <td>{{ $model['name'] }}</td>
            <td>{{ $model['content'] }}</td>
            <td>{{ $model['exact_location'] }}</td>
            <td>{{ $model['job_description'] }}</td>
            <td>{{ $model['requisites'] }}</td>
            <td>{{ $model['time'] }}</td>
            <td>{{ $model['inventory_number'] }}</td>
            <td>{{ $model['performer'] ? $model['performer']['name'] : '' }}</td>
            <td>{{ $model['date'] }}</td>
            <td>0</td>
            <td>0</td>
        </tr>
    </tbody>
</table>


</html>
