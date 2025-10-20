import React, { useState } from 'react';
import { PlusCircle, Trash2, DollarSign, TrendingUp, TrendingDown } from 'lucide-react';

export default function ExpenseTracker() {
  const [expenses, setExpenses] = useState([]);
  const [description, setDescription] = useState('');
  const [amount, setAmount] = useState('');
  const [category, setCategory] = useState('food');
  const [type, setType] = useState('expense');

  const categories = ['food', 'transport', 'bills', 'entertainment', 'shopping', 'other'];

  const addTransaction = (e) => {
    e.preventDefault();
    if (!description || !amount) return;

    const newExpense = {
      id: Date.now(),
      description,
      amount: parseFloat(amount),
      category,
      type,
      date: new Date().toLocaleDateString()
    };

    setExpenses([newExpense, ...expenses]);
    setDescription('');
    setAmount('');
  };

  const deleteExpense = (id) => {
    setExpenses(expenses.filter(exp => exp.id !== id));
  };

  const totalIncome = expenses
    .filter(exp => exp.type === 'income')
    .reduce((sum, exp) => sum + exp.amount, 0);

  const totalExpense = expenses
    .filter(exp => exp.type === 'expense')
    .reduce((sum, exp) => sum + exp.amount, 0);

  const balance = totalIncome - totalExpense;

  return (
    <div className="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 p-4">
      <div className="max-w-5xl mx-auto">
        <div className="bg-white rounded-2xl shadow-xl p-6 mb-6">
          <h1 className="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <DollarSign className="text-purple-600" />
            Expense Tracker
          </h1>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div className="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-green-100 text-sm">Total Income</p>
                  <p className="text-3xl font-bold">₱{totalIncome.toFixed(2)}</p>
                </div>
                <TrendingUp className="w-12 h-12 opacity-50" />
              </div>
            </div>

            <div className="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-red-100 text-sm">Total Expenses</p>
                  <p className="text-3xl font-bold">₱{totalExpense.toFixed(2)}</p>
                </div>
                <TrendingDown className="w-12 h-12 opacity-50" />
              </div>
            </div>

            <div className={`bg-gradient-to-br ${balance >= 0 ? 'from-blue-500 to-blue-600' : 'from-orange-500 to-orange-600'} rounded-xl p-6 text-white`}>
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-blue-100 text-sm">Balance</p>
                  <p className="text-3xl font-bold">₱{balance.toFixed(2)}</p>
                </div>
                <DollarSign className="w-12 h-12 opacity-50" />
              </div>
            </div>
          </div>

          <form onSubmit={addTransaction} className="bg-gray-50 rounded-xl p-6 mb-6">
            <h2 className="text-xl font-semibold text-gray-800 mb-4">Add Transaction</h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <input
                type="text"
                placeholder="Description"
                value={description}
                onChange={(e) => setDescription(e.target.value)}
                className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              />
              <input
                type="number"
                step="0.01"
                placeholder="Amount"
                value={amount}
                onChange={(e) => setAmount(e.target.value)}
                className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              />
              <select
                value={category}
                onChange={(e) => setCategory(e.target.value)}
                className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent capitalize"
              >
                {categories.map(cat => (
                  <option key={cat} value={cat}>{cat}</option>
                ))}
              </select>
              <select
                value={type}
                onChange={(e) => setType(e.target.value)}
                className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              >
                <option value="expense">Expense</option>
                <option value="income">Income</option>
              </select>
            </div>
            <button
              type="submit"
              className="mt-4 w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2 font-semibold"
            >
              <PlusCircle className="w-5 h-5" />
              Add Transaction
            </button>
          </form>

          <div className="space-y-3">
            <h2 className="text-xl font-semibold text-gray-800 mb-4">Transaction History</h2>
            {expenses.length === 0 ? (
              <p className="text-center text-gray-500 py-8">No transactions yet. Add your first transaction above!</p>
            ) : (
              expenses.map(exp => (
                <div
                  key={exp.id}
                  className={`flex items-center justify-between p-4 rounded-lg border-l-4 ${
                    exp.type === 'income' ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50'
                  }`}
                >
                  <div className="flex-1">
                    <div className="flex items-center gap-2">
                      <span className="font-semibold text-gray-800">{exp.description}</span>
                      <span className="text-xs bg-gray-200 px-2 py-1 rounded capitalize">{exp.category}</span>
                    </div>
                    <p className="text-sm text-gray-600">{exp.date}</p>
                  </div>
                  <div className="flex items-center gap-4">
                    <span className={`text-xl font-bold ${exp.type === 'income' ? 'text-green-600' : 'text-red-600'}`}>
                      {exp.type === 'income' ? '+' : '-'}₱{exp.amount.toFixed(2)}
                    </span>
                    <button
                      onClick={() => deleteExpense(exp.id)}
                      className="text-red-500 hover:text-red-700 transition"
                    >
                      <Trash2 className="w-5 h-5" />
                    </button>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      </div>
    </div>
  );
}