# Shop Tracker

Recovered Replit project from the original `shoptracker` folder.

## What It Does

Shop Tracker is a browser-based inventory and profit tracker. It lets you record item purchases, supplier names, buy prices, sell prices, quantities, sales, and price changes. The UI then calculates inventory left, average buy price, total profit, and purchase/sale history.

The app is self-contained in `index.php` and stores working data in browser `localStorage`, so the exported project does not include a database of shop records.

## Running Locally

Serve the folder with PHP:

```bash
php -S 0.0.0.0:8000 -t .
```

Then open `http://localhost:8000`.

## Recovery Notes

No stored inventory/customer data, credential-like secrets, IP logs, or large generated files were found during recovery. The old external `tracker.infiputer.repl.co` script include was removed from the recovered source.
