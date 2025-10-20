<?php

function addDays($numOfDays, $date = null)
{
    $date = $date ? strtotime($date) : time();
    return date('Y-m-d', strtotime("+$numOfDays days", $date));
}

function subDays($numOfDays, $date = null)
{
    $date = $date ? strtotime($date) : time();
    return date('Y-m-d', strtotime("-$numOfDays days", $date));
}
function numOfDay($date)
{
    return date('d', strtotime($date));
}
function numOfMonth($date)
{
    return date('m', strtotime($date));
}
function numOfYear($date)
{
    return date('Y', strtotime($date));
}
function getDay($date)
{
    return date('l', strtotime($date));
}
function getMonth($date)
{
    return date('F', strtotime($date));
}
function getYear($date)
{
    return date('Y', strtotime($date));
}
function currentDate()
{
    return date('Y-m-d');
}
function timeStamp($date = null)
{
    $date = $date ? strtotime($date) : time();
    return  strtotime($date);
}
function currentTimeStamp()
{
    return date('Y-m-d H:i:s');
}
function dateFormat($date)
{
    return date('d F, Y', strtotime($date));
}

function getStatusBadge($status)
{
    if ($status == 'Active')
        return 'badge-success ';
    if ($status == 'Inactive')
        return 'badge-warning';
    if ($status == 'Deleted')
        return 'badge-danger ';
}
function getStatusText($status)
{
    if ($status == 'Active') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Inactive') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-yellow-300 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Deleted') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Pending') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Approved') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Paid') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Rejected') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'In') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Out') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Present') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Absent') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">' . $status . '</span>';
    } elseif ($status == 'Backward') {
        return '<span class="px-2 py-1 text-xs font-semibold text-white rounded-full bg-amber-500">' . $status . '</span>';
    } else {
        return '<span class="px-2 py-1 text-xs font-semibold text-white bg-gray-500 rounded-full">' . $status . '</span>';
    }
}

function getLinkLabel($linkText = null, $icon = null, $class = null)
{
    return '<span title="' . $linkText . '" class="' . $class . '  " >' . $icon . ' ' . $linkText . '</span>';
}

function getStatusChangeBtn($status)
{
    if ($status == 'Active')
        return 'btn-secondary ';
    if ($status == 'Inactive')
        return 'btn-success';
}
function getApproveBtn()
{
    return 'btn-success';
}
function getRejectBtn()
{
    return 'btn-danger';
}
function getApproveIcon()
{
    return 'check-circle';
}
function getRejectIcon()
{
    return 'x-circle';
}
function getStatusChangeIcon($status)
{
    return ($status == 'Active') ? "x-circle" : "check-circle";
}

function regeneratePagination($datas, $total, $perPage, $currentPage)
{
    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $datas,
        $total,
        $perPage,
        $currentPage,
        ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
    );


    $query = request()->query();

    $paginator->appends($query);

    return $paginator;
}

function successResponse($message, $redirectUrl = null)
{
    if ($redirectUrl) {
        return redirect()->route($redirectUrl)->withSuccess($message);
    } else {
        return back()->withSuccess($message);
    }
}

function errorResponse($message, $redirectUrl = null)
{
    if ($redirectUrl) {
        return redirect()->route($redirectUrl)->withErrors($message);
    } else {
        return back()->withErrors($message);
    }
}

function warningResponse($message, $redirectUrl = null)
{
    if ($redirectUrl) {
        return redirect()->route($redirectUrl)->withWarning($message);
    } else {
        return back()->withWarning($message);
    }
}

