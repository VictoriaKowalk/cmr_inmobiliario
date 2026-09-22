$directorioOrigen = Join-Path $PSScriptRoot '..\database\datos\tokko\zona-norte\nodos'
$directorioDestino = Join-Path $PSScriptRoot '..\database\datos\tokko\zona-norte\nodos-segundo'
New-Item -ItemType Directory -Path $directorioDestino -Force | Out-Null

$ids = foreach ($archivo in Get-ChildItem -LiteralPath $directorioOrigen -Filter '*.json') {
    try {
        ((Get-Content -LiteralPath $archivo.FullName -Raw -Encoding UTF8 | ConvertFrom-Json).divisions | ForEach-Object { [int] $_.id })
    } catch {
        continue
    }
}

$pendientes = $ids | Sort-Object -Unique | Where-Object {
    -not (Test-Path -LiteralPath (Join-Path $directorioDestino "$_.json"))
}

foreach ($id in $pendientes) {
    $destino = Join-Path $directorioDestino "$id.json"
    curl.exe --silent --show-error --fail "https://www.tokkobroker.com/api/v1/location/$id/?lang=es_ar&format=json" -o $destino

    if ($LASTEXITCODE -ne 0) {
        Remove-Item -LiteralPath $destino -Force -ErrorAction SilentlyContinue
        break
    }

    Start-Sleep -Seconds 3
}
