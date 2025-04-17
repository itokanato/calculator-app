<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>計算機</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- ディスプレイ -->
                    <div class="bg-gray-100 p-4 rounded-lg mb-4 text-right text-3xl font-semibold">
                        <span id="display">0</span>
                    </div>

                    <!-- キーパッド -->
                    <div class="grid grid-cols-4 gap-2">
                        <!-- クリアボタン -->
                        <button onclick="clearDisplay()" class="col-span-2 bg-red-500 hover:bg-red-600 text-white p-4 rounded-lg font-semibold">
                            C
                        </button>
                        <!-- 削除ボタン -->
                        <button onclick="deleteLastChar()" class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg font-semibold">
                            ←
                        </button>
                        <!-- 演算子 -->
                        <button onclick="appendOperator('÷')" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg font-semibold">
                            ÷
                        </button>

                        <!-- 数字7-9 -->
                        <button onclick="appendNumber('7')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            7
                        </button>
                        <button onclick="appendNumber('8')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            8
                        </button>
                        <button onclick="appendNumber('9')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            9
                        </button>
                        <!-- 演算子 -->
                        <button onclick="appendOperator('×')" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg font-semibold">
                            ×
                        </button>

                        <!-- 数字4-6 -->
                        <button onclick="appendNumber('4')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            4
                        </button>
                        <button onclick="appendNumber('5')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            5
                        </button>
                        <button onclick="appendNumber('6')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            6
                        </button>
                        <!-- 演算子 -->
                        <button onclick="appendOperator('-')" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg font-semibold">
                            -
                        </button>

                        <!-- 数字1-3 -->
                        <button onclick="appendNumber('1')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            1
                        </button>
                        <button onclick="appendNumber('2')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            2
                        </button>
                        <button onclick="appendNumber('3')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            3
                        </button>
                        <!-- 演算子 -->
                        <button onclick="appendOperator('+')" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg font-semibold">
                            +
                        </button>

                        <!-- 数字0と小数点 -->
                        <button onclick="appendNumber('0')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            0
                        </button>
                        <button onclick="appendNumber('.')" class="bg-gray-200 hover:bg-gray-300 p-4 rounded-lg font-semibold">
                            .
                        </button>
                        <!-- 等号 -->
                        <button onclick="calculate()" class="col-span-2 bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg font-semibold">
                            =
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentInput = '';
        let firstNumber = null;
        let operator = null;
        let waitingForSecondNumber = false;

        function updateDisplay() {
            document.getElementById('display').textContent = currentInput || '0';
        }

        function appendNumber(number) {
            if (waitingForSecondNumber) {
                currentInput = number;
                waitingForSecondNumber = false;
            } else {
                currentInput = currentInput === '0' ? number : currentInput + number;
            }
            updateDisplay();
        }

        function appendOperator(op) {
            if (firstNumber === null) {
                firstNumber = parseFloat(currentInput);
            } else if (!waitingForSecondNumber) {
                calculate();
                firstNumber = parseFloat(currentInput);
            }
            operator = op;
            waitingForSecondNumber = true;
        }

        function clearDisplay() {
            currentInput = '';
            firstNumber = null;
            operator = null;
            waitingForSecondNumber = false;
            updateDisplay();
        }

        function deleteLastChar() {
            currentInput = currentInput.slice(0, -1);
            if (currentInput === '') {
                currentInput = '0';
            }
            updateDisplay();
        }

        function calculate() {
            if (firstNumber === null || operator === null || waitingForSecondNumber) {
                return;
            }

            const secondNumber = parseFloat(currentInput);
            
            fetch('/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    firstNumber: firstNumber,
                    secondNumber: secondNumber,
                    operator: operator
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    clearDisplay();
                } else {
                    currentInput = data.result.toString();
                    firstNumber = null;
                    operator = null;
                    waitingForSecondNumber = false;
                    updateDisplay();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('計算中にエラーが発生しました');
            });
        }

        // CSRFトークンの設定
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (!token) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }
        });
    </script>
</body>
</html>