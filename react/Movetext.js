import React from 'react';
import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';

const rows = [
  {
    n: '1',
    w: 'd4',
    b: 'Nf6'
  },
  {
    n: '2',
    w: 'c4',
    b: 'e6'
  },
  {
    n: '3',
    w: 'Nc3',
    b: 'Bb4'
  },
  {
    n: '4',
    w: 'Qc2',
    b: 'O-O'
  },
  {
    n: '5',
    w: 'a3',
    b: 'Bxc3+'
  },
  {
    n: '6',
    w: 'Qxc3',
    b: 'd6'
  },
  {
    n: '7',
    w: 'Bg5',
    b: 'Nbd7'
  },
  {
    n: '8',
    w: 'Nf3',
    b: 'b6'
  },
  {
    n: '9',
    w: 'e3',
    b: 'Bb7'
  },
  {
    n: '10',
    w: 'Nd2',
    b: 'Rc8'
  },
  {
    n: '11',
    w: 'Bd3',
    b: 'c5'
  },
  {
    n: '12',
    w: 'O-O',
    b: 'h6'
  },
  {
    n: '13',
    w: 'Bh4',
    b: 'cxd4'
  },
  {
    n: '14',
    w: 'exd4',
    b: 'd5'
  },
  {
    n: '15',
    w: 'b3',
    b: 'Qc7'
  },
  {
    n: '16',
    w: 'Rfe1',
    b: 'Nh5'
  },
  {
    n: '17',
    w: 'f3',
    b: 'Rfe8'
  },
  {
    n: '18',
    w: 'Rac1',
    b: 'dxc4'
  },
  {
    n: '19',
    w: 'bxc4',
    b: 'e5'
  },
  {
    n: '20',
    w: 'Bf5',
    b: 'exd4'
  },
  {
    n: '21',
    w: 'Qxd4',
    b: 'Bc6'
  },
  {
    n: '22',
    w: 'Ne4',
    b: 'Qf4'
  },
  {
    n: '23',
    w: 'Bxd7',
    b: 'Bxd7'
  },
  {
    n: '24',
    w: 'Bg3',
    b: 'Nxg3'
  },
  {
    n: '25',
    w: 'hxg3',
    b: 'Qc7'
  },
];

export default function Movetext() {
  return (
    <React.Fragment>
      <Table size="small">
        <TableHead>
          <TableRow>
            <TableCell>#</TableCell>
            <TableCell align="left">White</TableCell>
            <TableCell align="left">Black</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {rows.map((row) => (
            <TableRow key={row.n}>
              <TableCell>{row.n}</TableCell>
              <TableCell>{row.w}</TableCell>
              <TableCell>{row.b}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </React.Fragment>
  );
}
