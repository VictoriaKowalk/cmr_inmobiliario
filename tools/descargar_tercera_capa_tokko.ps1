$origen = Join-Path $PSScriptRoot '..\database\datos\tokko\zona-norte\nodos-segundo'
$destino = Join-Path $PSScriptRoot '..\database\datos\tokko\zona-norte\nodos-tercero'
New-Item -ItemType Directory -Path $destino -Force | Out-Null
$ids = foreach ($archivo in Get-ChildItem $origen -Filter '*.json') { $dato = Get-Content $archivo.FullName -Raw | ConvertFrom-Json; if (@($dato.divisions).Count -gt 0) { $dato.divisions.id } }
foreach ($id in ($ids | Sort-Object -Unique)) { $archivo = Join-Path $destino "$id.json"; if (Test-Path $archivo) { continue }; curl.exe --silent --show-error --fail "https://www.tokkobroker.com/api/v1/location/$id/?lang=es_ar&format=json" -o $archivo; if ($LASTEXITCODE -ne 0) { Remove-Item $archivo -Force -ErrorAction SilentlyContinue; break }; Start-Sleep 3 }
