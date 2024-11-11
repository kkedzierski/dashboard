#!/usr/bin/env bash

echo "Serving documentation locally..."
source myenv/bin/activate
mkdocs serve -f documentation/mkdocs.yml
  if [ $? -ne 0 ]; then
    echo "Failed to serve documentation locally."
    echo "Try install python virtual env by running 'python3 -m venv myenv' and try again."
    exit 1
  fi
