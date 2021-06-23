@extends('html')

@section('body')

    <h1>Job Queue Test Project</h1>

    <p>
        Thank you for applying for an engineer position at Newswire.<br>
        This skills test should take no more than 4 hours.
    </p>

    <p>
        This project is designed to test your skills in Laravel, SQL and VueJS.<br>
        Make sure you've run the database migrations before you start.
    </p>

    <p>
        This project introduces 3 concepts: <code>Jobs</code>, <code>Customers</code>, <code>Workers</code>.
        A job is scheduled by a customer (not part of this system).
        A worker views the list of available jobs.
        A worker reserves a job and works on it.
        A worker marks a job as completed.
        For simplicity <strong>there is no authentication.</strong>
        You can switch between workers using the link in the top right (available after running migrations).
    </p>

    <p>
        We already have an API route that lists the next 10 available jobs: <a href="{{ route('api.jobs.index') }}">api.jobs.index</a></code>.
    </p>

    <ol class="mb-4">
        <li>Optimize the API such that it responds in under 50ms.</li>
        <li>Populate the available jobs table below.</li>
        <li>Add functionality to assign an available job to yourself (reserve it).</li>
        <li>Update the API to allow assigned jobs to be queried.</li>
        <li>Populate the assigned jobs table below.</li>
        <li>Add functionality to filter on the description field.</li>
        <li>Optional: Write additional unit or feature tests.</li>
        <li>Optional: Improve the styling of this page.</li>
        <li>Optional: Allow jobs to be marked as complete.</li>
    </ol>

    <h3>Available Jobs</h3>
    <jobs-table></jobs-table>

    <h3>Assigned Jobs</h3>
    <jobs-table></jobs-table>

@endsection
