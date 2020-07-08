import React from 'react';
import { CartesianGrid, Legend, LineChart, Line, XAxis, YAxis, ResponsiveContainer, Tooltip } from 'recharts';
import clsx from 'clsx';
import useStyles from 'components/chart/styles';
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import Container from '@material-ui/core/Container';
import Grid from '@material-ui/core/Grid';
import Movetext from 'components/Movetext';
import Paper from '@material-ui/core/Paper';
import SevenTagRoster from 'components/SevenTagRoster';
import Typography from '@material-ui/core/Typography';

const data = [
  {
    n: '1', w: 5, b: 5,
  },
  {
    n: '2', w: 5, b: 6,
  },
  {
    n: '3', w: 5, b: 6,
  },
  {
    n: '4', w: 6, b: 7,
  },
  {
    n: '5', w: 7, b: 7,
  },
  {
    n: '6', w: 7, b: 7,
  },
  {
    n: '7', w: 7, b: 6,
  },
  {
    n: '8', w: 8, b: 7,
  },
  {
    n: '9', w: 7, b: 7,
  },
  {
    n: '10', w: 6, b: 7,
  },
  {
    n: '11', w: 7, b: 6,
  },
  {
    n: '12', w: 5, b: 5,
  },
  {
    n: '13', w: 6, b: 7,
  },
  {
    n: '14', w: 7, b: 8,
  },
  {
    n: '15', w: 7, b: 8,
  },
  {
    n: '16', w: 7, b: 9,
  },
  {
    n: '17', w: 6, b: 9,
  },
  {
    n: '18', w: 5, b: 9,
  },
  {
    n: '19', w: 7, b: 10,
  },
  {
    n: '20', w: 6, b: 11,
  },
  {
    n: '21', w: 7, b: 10,
  },
  {
    n: '22', w: 8, b: 11,
  },
  {
    n: '23', w: 7, b: 12,
  },
  {
    n: '24', w: 7, b: 13,
  },
  {
    n: '25', w: 7, b: 14,
  },
];

export default function KingSafetyChart() {
  const classes = useStyles();
  const fixedHeightPaper = clsx(classes.paper, classes.fixedHeight);

  return (
    <Container maxWidth="lg" className={classes.container}>
      <Breadcrumbs aria-label="breadcrumb" className={classes.breadcrumbs}>
        <Typography color="textSecondary">Heuristic</Typography>
        <Typography color="textPrimary">King safety</Typography>
      </Breadcrumbs>
      <Grid container spacing={3}>
        <Grid item xs={12} md={8} lg={9}>
          <Paper className={fixedHeightPaper}>
            <React.Fragment>
              <ResponsiveContainer>
                <LineChart
                  width={500}
                  height={300}
                  data={data}
                  margin={{
                    top: 5, right: 30, left: 20, bottom: 5,
                  }}
                >
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="n" />
                  <YAxis />
                  <Tooltip />
                  <Legend />
                  <Line type="monotone" dataKey="w" stroke="#82ca9d" fill="#82ca9d" strokeWidth={2} />
                  <Line type="monotone" dataKey="b" stroke="#8884d8" fill="#8884d8" strokeWidth={2} activeDot={{ r: 8 }} />
                </LineChart>
              </ResponsiveContainer>
            </React.Fragment>
          </Paper>
        </Grid>
        <Grid item xs={12} md={4} lg={3}>
          <Paper className={fixedHeightPaper}>
            <SevenTagRoster />
          </Paper>
        </Grid>
        <Grid item xs={12}>
          <Paper>
            <Movetext />
          </Paper>
        </Grid>
      </Grid>
    </Container>
  );
}
